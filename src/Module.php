<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace MelisEngine;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\ModuleManager;
use Zend\Db\TableGateway\TableGateway;
use Zend\Stdlib\Hydrator\ObjectProperty;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Stdlib\ArrayUtils;

use MelisEngine\Model\MelisTemplate;
use MelisEngine\Model\Tables\MelisTemplateTable;
use MelisEngine\Model\MelisPageLang;
use MelisEngine\Model\Tables\MelisPageLangTable;
use MelisEngine\Model\MelisPagePublished;
use MelisEngine\Model\Tables\MelisPagePublishedTable;
use MelisEngine\Model\MelisPageSaved;
use MelisEngine\Model\Tables\MelisPageSavedTable;
use MelisEngine\Model\MelisPageSeo;
use MelisEngine\Model\Tables\MelisPageSeoTable;
use MelisEngine\Model\MelisPageTree;
use MelisEngine\Model\Tables\MelisPageTreeTable;
use MelisEngine\Model\MelisSite;
use MelisEngine\Model\Tables\MelisSiteTable;
use MelisEngine\Model\MelisSiteDomain;
use MelisEngine\Model\Tables\MelisSiteDomainTable;
use MelisEngine\Model\MelisSite404;
use MelisEngine\Model\Tables\MelisSite404Table;
use MelisEngine\Model\MelisPlatformIds;
use MelisEngine\Model\Tables\MelisPlatformIdsTable;

use MelisEngine\Model\MelisCmsLang;
use MelisEngine\Model\Tables\MelisCmsLangTable;


class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        $this->createTranslations($e);
        
    }
    
    public function init(ModuleManager $mm)
    {
    	$mm->getEventManager()->getSharedManager()->attach(__NAMESPACE__,
    			MvcEvent::EVENT_DISPATCH, function($e) {
    				$e->getTarget()->layout('layout/layoutEngine');
    			}); 
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
    
    public function createTranslations($e, $locale = 'en_EN')
    {
    	$sm = $e->getApplication()->getServiceManager();
    	$translator = $sm->get('translator');

    	$translator->addTranslationFile('phparray', __DIR__ . '/../language/' . $locale . '.php');
    }
    
    public function getServiceConfig()
    {
    	return array(
    			'factories' => array(
    					'MelisEngine\Service\MelisPageService' =>  function($sm) {
    						$melisPageService = new \MelisEngine\Service\MelisPageService();
    						$melisPageService->setServiceLocator($sm);
    						return $melisPageService;
    					},
    					'MelisEngine\Service\MelisTreeService' =>  function($sm) {
    						$melisTreeService = new \MelisEngine\Service\MelisTreeService();
    						$melisTreeService->setServiceLocator($sm);
    						return $melisTreeService;
    					},
    					'MelisEngine\MelisPageColumns' =>  function($sm) {
    						$metadata = new \Zend\Db\Metadata\Metadata($sm->get('Zend\Db\Adapter\Adapter'));
    						$melisPageColumns = $metadata->getColumnNames('melis_cms_page_saved');
    						
    						return $melisPageColumns;
    					},
    					'MelisEngine\Model\Tables\MelisTemplateTable' =>  function($sm) {
    						return new MelisTemplateTable($sm->get('MelisTemplateTableGateway'));
    					},
    					'MelisTemplateTableGateway' => function ($sm) {
    						$hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisTemplate());
    						return new TableGateway('melis_cms_template', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);	
    					},
    					'MelisEngine\Model\Tables\MelisPageLangTable' =>  function($sm) {
    						return new MelisPageLangTable($sm->get('MelisPageLangTableGateway'));
    					},
    					'MelisPageLangTableGateway' => function ($sm) {
    						$hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisPageLang());
    						return new TableGateway('melis_cms_page_lang', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);	
    					},
    					'MelisEngine\Model\Tables\MelisPageTreeTable' =>  function($sm) {
    						return new MelisPageTreeTable($sm->get('MelisPageTreeTableGateway'));
    					},
    					'MelisPageTreeTableGateway' => function ($sm) {
    						$hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisPageTree());
    						return new TableGateway('melis_cms_page_tree', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);	
    					},
    					'MelisEngine\Model\Tables\MelisSiteTable' =>  function($sm) {
    						return new MelisSiteTable($sm->get('MelisSiteTableGateway'));
    					},
    					'MelisSiteTableGateway' => function ($sm) { 
    						$hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisSite());
    						return new TableGateway('melis_cms_site', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);	
    					}, 
    					'MelisEngine\Model\Tables\MelisSiteDomainTable' =>  function($sm) {
    						return new MelisSiteDomainTable($sm->get('MelisSiteDomainTableGateway'));
    					},
    					'MelisSiteDomainTableGateway' => function ($sm) { 
    						$hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisSiteDomain());
    						return new TableGateway('melis_cms_site_domain', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);	
    					},
    					'MelisEngine\Model\Tables\MelisSite404Table' =>  function($sm) {
    						return new MelisSite404Table($sm->get('MelisSite404TableGateway'));
    					},
    					'MelisSite404TableGateway' => function ($sm) { 
    						$hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisSite404());
    						return new TableGateway('melis_cms_site_404', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);	
    					},
    					'MelisEngine\Model\Tables\MelisPagePublishedTable' =>  function($sm) {
    						return new MelisPagePublishedTable($sm->get('MelisPagePublishedTableGateway'));
    					},
    					'MelisPagePublishedTableGateway' => function ($sm) {
    						$hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisPagePublished());
    						return new TableGateway('melis_cms_page_published', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);	
    					},
    					'MelisEngine\Model\Tables\MelisPageSavedTable' =>  function($sm) {
    						return new MelisPageSavedTable($sm->get('MelisPageSavedTableGateway'));
    					},
    					//
    					'MelisPageSavedTableGateway' => function ($sm) {
    						$hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisPageSaved());
    						return new TableGateway('melis_cms_page_saved', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);	
    					},
    					'MelisEngine\Model\Tables\MelisPageSeoTable' =>  function($sm) {
    						return new MelisPageSeoTable($sm->get('MelisPageSeoTableGateway'));
    					},
    					'MelisPageSeoTableGateway' => function ($sm) {
    						$hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisPageSeo());
    						return new TableGateway('melis_cms_page_seo', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);	
    					},
    					'MelisEngine\Model\Tables\MelisPlatformIdsTable' =>  function($sm) {
    						return new MelisPlatformIdsTable($sm->get('MelisPlatformIdsTableGateway'));
    					},
    					'MelisPlatformIdsTableGateway' => function ($sm) {
    						$hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisPlatformIds());
    						return new TableGateway('melis_cms_platform_ids', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);	
    					},
    					//
    					'MelisEngine\Model\Tables\MelisCmsLangTable' =>  function($sm) {
    					   return new MelisCmsLangTable($sm->get('MelisCmsLangTableGateway'));
    					},
    					'MelisCmsLangTableGateway' => function ($sm) {
    					   $hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisCmsLang());
    					   return new TableGateway('melis_cms_lang', $sm->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
    					},
    					
    					'MelisEngine\Service\MelisSearchService' => function ($sm) {
        					$melisSearchService = new \MelisEngine\Service\MelisSearchService();
        					$melisSearchService->setServiceLocator($sm);
        					 
        					return $melisSearchService;
    					},
    			),
    	);
    }
    
}
