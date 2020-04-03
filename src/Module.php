<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine;

use Laminas\Mvc\ModuleRouteListener;
use Laminas\Mvc\MvcEvent;
use Laminas\ModuleManager\ModuleManager;
use Laminas\Stdlib\ArrayUtils;

use MelisEngine\Listener\MelisEngineTreeServiceMicroServiceListener;
use MelisEngine\Listener\MelisEngineMicroServicePageServiceListener;

/**
 * Class Module
 * @package MelisEngine
 */
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
        
        (new MelisEngineTreeServiceMicroServiceListener())->attach($eventManager);
        (new MelisEngineMicroServicePageServiceListener())->attach($eventManager);
    }
    
    public function getConfig()
    {
    	$config = [];
    	$configFiles = [
            include __DIR__ . '/../config/module.config.php',
            include __DIR__ . '/../config/diagnostic.config.php',
            include __DIR__ . '/../config/app.microservice.php',
            include __DIR__ . '/../config/app.install.php'
    	];

    	foreach ($configFiles as $file)
    		$config = ArrayUtils::merge($config, $file);

    	return $config;
    }

    public function getAutoloaderConfig()
    {
        return [
            'Laminas\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ],
            ],
        ];
    }
    
    public function createTranslations($e, $locale = 'en_EN')
    {
    	$sm = $e->getApplication()->getServiceManager();
    	$translator = $sm->get('translator');
        
    	if (!empty($locale)){
    	    $translationType = [
    	        'interface',
    	        'install',
    	    ];
    	    
    	    $translationList = [];
    	    if(file_exists($_SERVER['DOCUMENT_ROOT'].'/../module/MelisModuleConfig/config/translation.list.php')){
                $translationList = include 'module/MelisModuleConfig/config/translation.list.php';
            }

            foreach($translationType as $type){
                
                $transPath = '';
                $moduleTrans = __NAMESPACE__."/$locale.$type.php";
                
                if(in_array($moduleTrans, $translationList)){
                    $transPath = "module/MelisModuleConfig/languages/".$moduleTrans;
                }

                if(empty($transPath)){
                    
                    // if translation is not found, use melis default translations
                    $defaultLocale = (file_exists(__DIR__ . "/../language/$locale.$type.php"))? $locale : "en_EN";
                    $transPath = __DIR__ . "/../language/$defaultLocale.$type.php";
                }
                
                $translator->addTranslationFile('phparray', $transPath);
            }
    	}
    }
}
