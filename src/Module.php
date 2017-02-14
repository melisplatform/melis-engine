<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\ModuleManager;
use Zend\Stdlib\ArrayUtils;


class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $sm = $e->getApplication()->getServiceManager();
        $routeMatch = $sm->get('router')->match($sm->get('request'));
        if (!empty($routeMatch))
        {
            $routeName = $routeMatch->getMatchedRouteName();
            $module = explode('/', $routeName);
             
            if (!empty($module[0]))
            {
                if ($module[0] == 'melis-backoffice')
                {
                    $eventManager->getSharedManager()->attach(__NAMESPACE__,
                        MvcEvent::EVENT_DISPATCH, function($e) {
                        				$e->getTarget()->layout('layout/layoutEngine');
                        });
                }
            }
        }
        
        $this->createTranslations($e);
        
    }
    
    public function init(ModuleManager $mm)
    {
    }

    public function getConfig()
    {
    	$config = array();
    	$configFiles = array(
    			include __DIR__ . '/../config/module.config.php',
    	);
    	
    	foreach ($configFiles as $file) {
    		$config = ArrayUtils::merge($config, $file);
    	} 
    	
    	return $config;
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        ); 
    }
    
    public function createTranslations($e, $locale = 'en_EN')
    {
    	$sm = $e->getApplication()->getServiceManager();
    	$translator = $sm->get('translator');
        
    	if (!empty($locale)){
    	    
    	    // Inteface translations
    	    $interfaceTransPath = 'module/MelisModuleConfig/languages/MelisEngine/' . $locale . '.interface.php';
    	    $default = __DIR__ . '/../language/en_EN.interface.php';
    	    $transPath = (file_exists($interfaceTransPath))? $interfaceTransPath : $default;
    	    $translator->addTranslationFile('phparray', $transPath);
    	    
    	}
    }
    
}
