<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\View\Model\ViewModel;
use Zend\Stdlib\ArrayUtils;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerInterface;

/**
 * This is the generic controller plugin that tools use
 * to give pre-created blocks to the front office.
 * The business logic is implemented in plugins, the views are customized in the website.
 * This class just make the association between the view created and its template.
 *
 */
abstract class MelisTemplatingPlugin extends AbstractPlugin  implements ServiceLocatorAwareInterface 
{
    protected $pluginName;
    protected $pluginConfig = array();
    protected $pluginFrontConfig = array();
    protected $pluginBackConfig = array();
    protected $renderMode;

    protected $defaultPluginConfig = array();
    protected $updatesPluginConfig = array();

    protected $serviceLocator;
    protected $eventManager;
    
    
    // To call to get front view
    abstract public function front();

    // To call to get back office view
    abstract public function back();
    
    
    public function __construct($updatesPluginConfig = array())
    {
        $className = explode('\\', get_class($this));
        if (count($className) > 0)
            $className = $className[count($className) - 1];
        $this->pluginName = $className;
        $this->setEventManager(new EventManager());
    }
    
    public function getServiceLocator() {
        return $this->serviceLocator->getServiceLocator();
    }
    
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
        $this->serviceLocator = $serviceLocator;
    }
    
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $this->eventManager = $eventManager;
    }
    
    public function getEventManager()
    {
        return $this->eventManager;
    }
    
    public function render($updatesPluginConfig = array())
    {
        $router = $this->getServiceLocator()->get('router');
        $request = $this->getServiceLocator()->get('request');
        
        $this->renderMode = 'front';
        $routeMatch = $router->match($request);
        if (!empty($routeMatch))
        {
            $params = $routeMatch->getParams();
            if (!empty($params['renderMode']))
                $this->renderMode = $params['renderMode'];
        }
        
        $melisEngineGeneralService = $this->getServiceLocator()->get('MelisEngineGeneralService');
        $updatesPluginConfig = $melisEngineGeneralService->sendEvent($this->pluginName . '_melistemplating_plugin_start', $updatesPluginConfig);
        
        // Send event before creating the plugin object with its parameters
        $this->updatesPluginConfig = $updatesPluginConfig;
        
        $this->getPluginConfigs($this->renderMode);
        
        if ($this->renderMode == 'front')
            $view = $this->sendViewResult($this->front());
        else 
        {
            // For now as no back office management is implemented
            $this->getPluginConfigs('front');
            $view = $this->sendViewResult($this->front());
            
            // When back() functions implemented
            // $view = $this->sendViewResult($this->back());
        }
        
        return $view;
    }
    
    protected function formatGetPostInArray($parameters)
    {
        $parametersResults = array();
        
        foreach ($parameters as $key => $value)
        {
            $tmp = explode('-', $key);
            if (count($tmp) == 1)
                $parametersResults[$key] = $value;
            else
            {
                $children = array();
                $lastKey = '';
                for ($i = count($tmp) - 1; $i >= 0; $i--)
                {
                    $lastKey = $tmp[$i];
    
                    if ($i == count($tmp) - 1)
                        $children[$tmp[$i]] = $value;
                        else
                        {
                            $arrayTmp = $children;
                            $children = array();
                            $children[$tmp[$i]] = $arrayTmp;
                        }
    
                        if ($i < 0)
                            die;
                }
    
                $parametersResults[$lastKey] = $children[$lastKey];
            }
        }
        
        return $parametersResults;
    }
    
    public function getPluginConfigs($renderMode)
    {
        $config = $this->getServiceLocator()->get('config');
        if (!empty($config['plugins'][$this->configPluginKey]['plugins']))
            $this->defaultPluginConfig = $config['plugins'][$this->configPluginKey]['plugins'][$this->pluginName];
        /**
         * Merging configs:
         * - gets the default in app.plugins.php
         * - merge with the one hardcoded-provided in parameters of render
         * - merge with GET parameters, children nodes separated with a "-", ex: pagination-current_page
         * - merge with POST parameters, children nodes separated with a "-", ex: pagination-current_page
         */
        // merging default with parameters config
            
        $this->pluginConfig[$renderMode] = ArrayUtils::merge($this->defaultPluginConfig[$renderMode], $this->updatesPluginConfig);

        $request = $this->getServiceLocator()->get('request');
        
        // merging with GET
        $parameters = $request->getQuery()->toArray();
        $parametersResults = $this->formatGetPostInArray($parameters);
        $this->pluginConfig[$renderMode] = ArrayUtils::merge($this->pluginConfig[$renderMode], $parametersResults);

        // merging with POST
        $parameters = $request->getPost()->toArray();
        $parametersResults = $this->formatGetPostInArray($parameters);
        
        $finalConfig = ArrayUtils::merge($this->pluginConfig[$renderMode], $parametersResults);
        
        $finalConfig = $this->translateAppConfig($finalConfig);
        
        $this->pluginConfig[$renderMode] = $finalConfig;      
        
        if (!empty($this->pluginConfig['front']))
            $this->pluginFrontConfig = $this->pluginConfig[$renderMode];
        if (!empty($this->pluginConfig['melis']))
            $this->pluginBackConfig = $this->pluginConfig[$renderMode];
    }
    
    public function translateAppConfig($array)
    {
        $translator = $this->getServiceLocator()->get('translator');
    
        $final = array();
        foreach($array as $key => $value)
        {
            if (is_array($value))
            {
                $children = $this->translateAppConfig($value);
                $final[$key] = $children;
            }
            else
            {
                if (substr($value, 0, 3) == 'tr_')
                {
                    $value = $translator->translate($value);
                }
                    
                $final[$key] = $value;
            }
        }
        
        return $final;
    }
    
    public function sendViewResult($variables)
    {
        // Send event before creating the view
        $melisEngineGeneralService = $this->getServiceLocator()->get('MelisEngineGeneralService');
        $variables = $melisEngineGeneralService->sendEvent($this->pluginName . '_melistemplating_plugin_generate_view', $variables);

        $viewModel = new ViewModel();
        
        foreach ($variables as $keyVar => $valueVar)
            $viewModel->$keyVar = $valueVar;
        
        if (!empty($this->pluginFrontConfig['template_path']))
            $viewModel->setTemplate($this->pluginFrontConfig['template_path']);
        else 
            $viewModel->setTemplate('melis-engine/plugins/notemplate');

        $viewModel = $melisEngineGeneralService->sendEvent($this->pluginName . '_melistemplating_plugin_end', array('view' => $viewModel));
            
        return $viewModel['view'];
    }
    
    public function sendEvent($eventName, $parameters)
    {
        $parameters = $this->eventManager->prepareArgs($parameters);
        $this->eventManager->trigger($eventName, $this, $parameters);
        $parameterss = get_object_vars($parameters);
    
        return $parameters;
    }
}
