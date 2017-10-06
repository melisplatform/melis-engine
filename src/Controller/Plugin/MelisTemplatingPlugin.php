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
use Zend\View\Model\JsonModel;
use Zend\Stdlib\ArrayUtils;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerInterface;
use Zend\Session\Container;

/**
 * This is the generic controller plugin that tools use
 * to give pre-created blocks to the front office.
 * The business logic is implemented in plugins, the views are customized in the website.
 * This class just make the association between the view created and its template.
 *
 */
abstract class MelisTemplatingPlugin extends AbstractPlugin  implements ServiceLocatorAwareInterface
{
    // the key of the configuration in the app.plugins.php
    protected $configPluginKey;
    protected $pluginName;
    
    // When used in page mode with content saved in DB
    protected $pluginXmlDbKey   = '';
    protected $pluginXmlDbValue = '';
    
    protected $pluginHardcoded    = true;
    protected $fromDragDropZone   = false;
    protected $encapsulatedPlugin = true;
    
    protected $pluginConfig      = array();
    protected $pluginFrontConfig = array();
    protected $pluginBackConfig  = array();
    
    protected $renderMode;
    protected $previewMode;
    
    protected $defaultPluginConfig = array();
    protected $updatesPluginConfig = array();
    
    protected $fullTemplateList    = array();

    protected $widthDesktop = 100;
    protected $widthTablet  = 100;
    protected $widthMobile  = 100;

    protected $pluginContainerId = null;

    protected $serviceLocator;
    protected $eventManager;
    
    
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
    
    // To call to get front view
    abstract public function front();
    
    // To call to get back office view
    public function back()
    {
        $viewModel = new ViewModel();
        $viewModel->setTemplate('melis-engine/plugins/meliscontainer');
        $viewModel->configPluginKey    = $this->configPluginKey;
        $viewModel->pluginName         = $this->pluginName;
        $viewModel->pluginBackConfig   = $this->pluginBackConfig;
        $viewModel->pluginFrontConfig  = $this->pluginFrontConfig;
        $viewModel->pluginHardcoded    = $this->pluginHardcoded;
        $viewModel->hardcodedConfig    = $this->updatesPluginConfig;
        $viewModel->fromDragDropZone   = $this->fromDragDropZone;
        $viewModel->encapsulatedPlugin = $this->encapsulatedPlugin;

        $viewModel->widthDesktop      = $this->widthDesktop;
        $viewModel->widthTablet       = $this->widthTablet;
        $viewModel->widthMobile       = $this->widthMobile;
        $viewModel->pluginContainerId = $this->pluginContainerId;

        $pageId = (!empty($this->pluginFrontConfig['pageId'])) ? $this->pluginFrontConfig['pageId'] : 0;
        $viewModel->pageId = $pageId;
        $viewModel->pluginXmlDbKey = $this->pluginXmlDbKey;
        
        $siteModule = getenv('MELIS_MODULE');
        $melisPage = $this->getServiceLocator()->get('MelisEnginePage');
        $datasPage = $melisPage->getDatasPage($pageId, 'saved');
        if($datasPage)
        {
            $datasTemplate = $datasPage->getMelisTemplate();
            
            if(!empty($datasTemplate))
            {
                $siteModule = $datasTemplate->tpl_zf2_website_folder;
            }
        }
        
        
        $viewModel->siteModule = $siteModule;
        
        return $viewModel;
    }
    
    // Automatically called when generating the final config, should be overriden in plugins
    public function loadDbXmlToPluginConfig()
    {
        return array();
    }
    
    // Automatically called when saving the final config, should be overriden in plugins
    public function savePluginConfigToXml($parameters)
    {
        return '';
    }
    
    // Creates the plugin parameter form, override this function in your plugin
    // in order to show a specific form. Default shows nothing
    public function createOptionsForms()
    {
        $viewModel = new ViewModel();
        $viewModel->setTemplate('melis-engine/plugins/noformtemplate');
        
        $viewRender = $this->getServiceLocator()->get('ViewRenderer');
        $html = $viewRender->render($viewModel);
        
        return array(array('name' => $this->pluginName, 'html' => $html, 'empty' => true));
    }
    
    /**
     * This setter can be used to set a hardcoded config if not given through render function
     *
     * @param array $updatesConfig
     */
    public function setUpdatesPluginConfig($updatesConfig)
    {
        $this->updatesPluginConfig = $updatesConfig;
    }
    
    /**
     * This function renders the plugin in the template in front or back end mode
     * depending on the renderMode parameter
     *
     * @param array $updatesPluginConfig
     */
    public function render($updatesPluginConfig = array(), $generatePluginId = false, $forceRenderModeToFront = false)
    {
        $router = $this->getServiceLocator()->get('router');
        $request = $this->getServiceLocator()->get('request');
        $routeMatch = $router->match($request);
        
        if (!$forceRenderModeToFront)
        {
            $this->renderMode = 'front';
            if (!empty($routeMatch))
            {
                $params = $routeMatch->getParams();
                if (!empty($params['renderMode']))
                    $this->renderMode = $params['renderMode'];
            }
        }
        else
            $this->renderMode = 'front';
            
            $this->previewMode = false;
            if (!empty($routeMatch))
            {
                $params = $routeMatch->getParams();
                if (!empty($params['preview']))
                    $this->previewMode = $params['preview'];
                    
                    if (!empty($params['idpage']))
                        $updatesPluginConfig['pageId'] = $params['idpage'];
            }
            
            $melisEngineGeneralService = $this->getServiceLocator()->get('MelisEngineGeneralService');
            $updatesPluginConfig = $melisEngineGeneralService->sendEvent($this->pluginName . '_melistemplating_plugin_start', $updatesPluginConfig);
            
            // Send event before creating the plugin object with its parameters
            $this->updatesPluginConfig = $updatesPluginConfig;
            
            $this->getPluginConfigs($generatePluginId);
            
            if ($this->renderMode == 'front' || $this->previewMode)
                $view = $this->sendViewResult($this->front());
            else
            {
                $viewFront = $this->sendViewResult($this->front());
                
                $view = $this->back();
                
                if ($view InstanceOf ViewModel)
                {
                    $viewRender = $this->getServiceLocator()->get('ViewRenderer');
                    $viewFrontRendered = $viewRender->render($viewFront);
                    
                    $view->viewFront = $viewFrontRendered;
                }
                else
                    $view = $viewFront;
                    
            }
            
            return $view;
    }
    
    /**
     * This function gets the datas associated with this plugin from the page's XML in DB
     */
    protected function getPluginValueFromDb()
    {
        $this->pluginXmlDbValue = '';
        $xmlPage = '';
        $melisPage = $this->getServiceLocator()->get('MelisEnginePage');
        
        $id = (!empty($this->pluginConfig['front']['id'])) ? $this->pluginConfig['front']['id'] : null;
        $pageId = (!empty($this->pluginConfig['front']['pageId'])) ? $this->pluginConfig['front']['pageId'] : null;
        // This overides the pageId if the plugin requesting is MelisTag
        $pageId = (!empty($this->pluginConfig['front']['tagPageId'])) ? $this->pluginConfig['front']['tagPageId'] : $pageId;
        if (empty($pageId) || empty($id))
        {
            // The plugin is not used with a pageId an item id, so nothing to get in DB
            return;
        }
        
        $datasPage = array();
        $valueSetted = false;
        if ($this->renderMode == 'front')
            $datasPage = $melisPage->getDatasPage($pageId, 'published');
        else
        {
            // Melis BO's display, first check if there's something in session
            $container = new Container('meliscms');
            if (!empty($container['content-pages'][$pageId]) && !$this->previewMode)
            {
                if (!empty($container['content-pages'][$pageId][$this->pluginXmlDbKey][$id]))
                    $this->pluginXmlDbValue = $container['content-pages'][$pageId][$this->pluginXmlDbKey][$id];
                else
                    $this->pluginXmlDbValue = '';
                    $valueSetted = true;
            }
            else
            {
                $datasPage = $melisPage->getDatasPage($pageId, 'saved');
            }
        }


        $datasPageTree = array();
        if (!empty($datasPage))
            $datasPageTree = $datasPage->getMelisPageTree()->getArrayCopy();

        // Attach this event if datas must come from somewhere else and override
        $eventParams = array(
            'pageId' => $pageId,
            'previewMode' => $this->previewMode,
            'renderMode' => $this->renderMode,
            'pluginId' => $id,
            'pluginXmlDbKey' => $this->pluginXmlDbKey,
            'pluginModule' => $this->configPluginKey,
            'pluginName' => $this->pluginName,
            'actualDatasPageTree' => $datasPageTree,
        );
        $melisEngineGeneralService = $this->getServiceLocator()->get('MelisEngineGeneralService');
        $datasPageTree = $melisEngineGeneralService->sendEvent('melistemplating_plugin_get_datas_db', $eventParams);
        $datasPageTree = $datasPageTree['actualDatasPageTree'];
            

        if (!$valueSetted)
        {
            $xmlPage = $datasPageTree['page_content'];
            $xml = simplexml_load_string($xmlPage);
            
            if ($xml)
            {
                foreach ($xml as $namePlugin => $valuePlugin)
                {
                    if ($namePlugin == $this->pluginXmlDbKey)
                    {
                        if (!empty($valuePlugin->attributes()->id) &&
                            (string)$valuePlugin->attributes()->id == $id)
                        {
                            $this->pluginXmlDbValue = $valuePlugin->asXML();
                            break;
                        }
                    }
                }
            }
        }
    }

    protected function getPluginWidths()
    {
        $xml = simplexml_load_string($this->pluginXmlDbValue);
        if ($xml)
        {
            if(!empty($xml->attributes()->width_desktop))
                $this->widthDesktop = (string) $xml->attributes()->width_desktop;

            if(!empty($xml->attributes()->width_tablet))
                $this->widthTablet  = (string) $xml->attributes()->width_tablet;

            if(!empty($xml->attributes()->width_mobile))
                $this->widthMobile  = (string) $xml->attributes()->width_mobile;

            if (!empty($xml->attributes()->plugin_container_id))
                $this->pluginContainerId = (string) $xml->attributes()->plugin_container_id;

        }
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
    
    public function getPluginConfigs($generatePluginId = false)
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
        
        if (!empty($this->updatesPluginConfig['template_path']) && !is_array($this->updatesPluginConfig['template_path']))
        {
            $this->updatesPluginConfig['template_path'] = array($this->updatesPluginConfig['template_path']);
        }
        $this->pluginConfig['front'] = ArrayUtils::merge($this->defaultPluginConfig['front'], $this->updatesPluginConfig);
        
        if ($generatePluginId)
        {
            $finalConfig = $this->translateAppConfig($this->pluginConfig['front']);
        }
        else
        {
            $request = $this->getServiceLocator()->get('request');
            
            // merge with DB values
            $this->getPluginValueFromDb();
            $this->getPluginWidths();

            $this->pluginConfig['front'] = ArrayUtils::merge($this->pluginConfig['front'], $this->loadDbXmlToPluginConfig());

            // merging with GET
            $parameters = $request->getQuery()->toArray();
            $parametersResults = $this->formatGetPostInArray($parameters);
            $this->pluginConfig['front'] = ArrayUtils::merge($this->pluginConfig['front'], $parametersResults);
            
            // merging with POST
            $parameters = $request->getPost()->toArray();
            $parametersResults = $this->formatGetPostInArray($parameters);
            
            $finalConfig = ArrayUtils::merge($this->pluginConfig['front'], $parametersResults);
            $finalConfig = $this->translateAppConfig($finalConfig);
        }

        // add plugin widths configuration
        $finalConfig['widthDesktop']      = $this->widthDesktop;
        $finalConfig['widthTablet']       = $this->widthTablet;
        $finalConfig['widthMobile']       = $this->widthMobile;
        $finalConfig['pluginContainerId'] = $this->pluginContainerId;
        
        // Getting the final config for templatePath
        if (is_array($finalConfig['template_path']))
        {
            $this->fullTemplateList = $finalConfig['template_path'];
            $finalConfig['template_path'] = $finalConfig['template_path'][count($finalConfig['template_path']) - 1];
        }
        else
            $this->fullTemplateList = array($finalConfig['template_path']);
            
        // Generate pluginId if needed
        if ($generatePluginId)
        {
            $newPluginId = time();
            if (!empty($finalConfig['id']))
                $newPluginId = $finalConfig['id'] . '_' . $newPluginId;
                $finalConfig['id'] = $newPluginId;
        }
        
        $this->pluginConfig['front'] = $finalConfig;

        
        if (!empty($this->pluginConfig['front']))
            $this->pluginFrontConfig = $this->pluginConfig['front'];
            
        $this->pluginBackConfig = $this->translateAppConfig($this->defaultPluginConfig['melis']);

        // Getting JS/CSS files for auto adding in front and back if necessary
        $this->savePluginRessources('plugins_front', $this->pluginFrontConfig);
        $this->savePluginRessources('plugins_front', $this->pluginFrontConfig);
        if ($this->renderMode == 'melis')
        {
            $this->savePluginRessources('plugins_melis', $this->pluginBackConfig);
            $this->savePluginRessources('plugins_melis', $this->pluginBackConfig);
        }
    }
    
    public function savePluginRessources($keyRessource, $array)
    {
        if ($this->getServiceLocator()->get('templating_plugins')->hasItem($keyRessource))
        {
            $files = $this->getServiceLocator()->get('templating_plugins')->getItem($keyRessource);
        }
        else
        {
            $files = array('js' => array(), 'css' => array());
        }
        
        if (!empty($array['files']) && !empty($array['files']['js']))
            foreach ($array['files']['js'] as $key => $value)
            {
                if ($value != 'disable')
                    $files['js'][$this->pluginName . '_' . $key] = $value;
            }
        
        if (!empty($array['files']) && !empty($array['files']['css']))
            foreach ($array['files']['css'] as $key => $value)
                if ($value != 'disable')
                    $files['css'][$this->pluginName . '_' . $key] = $value;
                    
        $this->getServiceLocator()->get('templating_plugins')->setItem($keyRessource, $files);
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
        
        if($variables instanceof JsonModel) {
            // for JSON response
            $request           = $this->getServiceLocator()->get('request');
            $variables         = $variables->getVariables();
            $pluginId          = $variables['pluginId'];
            $submittedPluginId = isset($variables['submittedPluginId']) ? $variables['submittedPluginId'] : '';
            
            if( $request->isPost() &&
                (!is_null($submittedPluginId) && ($pluginId == $submittedPluginId))) {
                    // only return the JSON that we need, or the form that has been submitted.
                    unset($variables['submittedPluginId']);
                    $model = new JsonModel($variables);
                    return $model;
                }
        }
        else {
            
            $model = new ViewModel();
            // Send event before creating the view
            $melisEngineGeneralService = $this->getServiceLocator()->get('MelisEngineGeneralService');
            
            $variables = $melisEngineGeneralService->sendEvent($this->pluginName . '_melistemplating_plugin_generate_view', $variables);
            
            foreach ($variables as $keyVar => $valueVar)
                if ($valueVar instanceof ViewModel)
                    $model->addChild($valueVar, $keyVar);
                else
                    $model->$keyVar = $valueVar;
                        
            if (!empty($this->pluginFrontConfig['template_path']))
            {
                $config = $this->getServiceLocator()->get('config');
                // checking if at least the template is declared
                if (!empty($config['view_manager']['template_map'][$this->pluginFrontConfig['template_path']]))
                {
                    $model->setTemplate($this->pluginFrontConfig['template_path']);
                }
                else
                    $model->setTemplate('melis-engine/plugins/notemplate');
            }
            else
                $model->setTemplate('melis-engine/plugins/notemplate');
                
                $model = $melisEngineGeneralService->sendEvent($this->pluginName . '_melistemplating_plugin_end', array('view' => $model));

            if(isset($model['view']) && ($model['view'] instanceof ViewModel)) {
                // add with variables to plugin view
                $model['view']->setVariables(array(
                    'widthDesktop'      => 'plugin-width-lg-'.round($this->widthDesktop),
                    'widthTablet'       => 'plugin-width-md-'.round($this->widthTablet),
                    'widthMobile'       => 'plugin-width-xs-'.round($this->widthMobile),
                    'pluginContainerId' => $this->pluginContainerId
                ));

            }

            return $model['view'];
        }
    }
    
    public function setPluginHardcoded($value)
    {
        $this->pluginHardcoded = $value;
    }
    
    public function setPluginFromDragDrop($value)
    {
        $this->fromDragDropZone = $value;
    }
    
    public function getPluginXmlDbKey()
    {
        return $this->pluginXmlDbKey;
    }
    
    public function getPluginFrontConfig()
    {
        return $this->pluginFrontConfig;
    }
    
    public function setEncapsulatedPlugin($value)
    {
        $this->encapsulatedPlugin = $value;
    }
    
    /**
     * Returns the data to populate the form inside the modals when invoked
     * @return array|bool|null
     */
    public function getFormData()
    {
        $data     = null;
        
        // formats the configuration into single array, in order to fill-out the forms with the current pluginFrontConfig value
        $configData  = function($arr, $data = []) use (&$configData) {
            foreach($arr as $key => $items) {
                if(is_array($items)) {
                    foreach($items as $childKey => $childItems) {
                        if(!is_array($childItems)) {
                            $data[$childKey] = $childItems;
                        }
                    }
                    $configData($items, $data);
                }
                else {
                    $data[$key] = $items;
                }
            }
            return $data;
        };
        $data = $configData($this->pluginFrontConfig);
        
        return $data;
    }
}