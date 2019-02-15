<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2015 Melis Technology (http://www.melistechnology.com)
 *
 */

return array(
    'router' => array(
        'routes' => array(
            'melis-backoffice' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/melis[/]',
                ),
                'child_routes' => array(
                    'application-MelisEngine' => array(
                        'type'    => 'Literal',
                        'options' => array(
                            'route'    => 'MelisEngine',
                            'defaults' => array(
                                '__NAMESPACE__' => 'MelisEngine\Controller',
                                'controller'    => 'MelisSetup',
                                'action'        => 'setup-form',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'default' => array(
                                'type'    => 'Segment',
                                'options' => array(
                                    'route'    => '/[:controller[/:action]]',
                                    'constraints' => array(
                                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                    ),
                                    'defaults' => array(
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            /*
             * This route will handle the
             * alone setup of a module
             */
            'setup-melis-engine' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/MelisEngine',
                    'defaults' => array(
                        '__NAMESPACE__' => 'MelisEngine\Controller',
                        'controller'    => 'MelisSetup',
                        'action'        => 'setup-form',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
//
                            ),
                        ),
                    ),
                    'setup' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route' => '/setup',
                            'defaults' => array(
                                'controller' => 'MelisEngine\Controller\MelisSetup',
                                'action' => 'setup-form',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'translator' => array(
    	'locale' => 'en_EN',
	),
    'service_manager' => array(
		'invokables' => array(
			'MelisEngine\Service\MelisPageServiceInterface' => 'MelisEngine\Service\MelisPageService',
			'MelisEngine\Service\MelisTreeServiceInterface' => 'MelisEngine\Service\MelisTreeService',
		    'MelisEngine\Service\MelisEngineSendMailInterface' => 'MelisEngine\Service\MelisEngineSendMailService',
		    'MelisEngine\Service\MelisEngineStyleInterface' => 'MelisEngine\Service\MelisEngineStyleService',
		    'MelisEngine\Service\MelisEngineLangInterface' => 'MelisEngine\Service\MelisEngineLangService'

		),
		'aliases' => array(
			'MelisEngineTablePlatformIds' => 'MelisEngine\Model\Tables\MelisPlatformIdsTable',
			'MelisEngineTableTemplate' => 'MelisEngine\Model\Tables\MelisTemplateTable',
			'MelisEngineTablePageLang' => 'MelisEngine\Model\Tables\MelisPageLangTable',
			'MelisEngineTablePageTree' => 'MelisEngine\Model\Tables\MelisPageTreeTable',
			'MelisEngineTableSite' => 'MelisEngine\Model\Tables\MelisSiteTable',
			'MelisEngineTableSiteDomain' => 'MelisEngine\Model\Tables\MelisSiteDomainTable',
			'MelisEngineTableSite404' => 'MelisEngine\Model\Tables\MelisSite404Table',
			'MelisEngineTableSite301' => 'MelisEngine\Model\Tables\MelisSite301Table',
			'MelisEngineTablePagePublished' => 'MelisEngine\Model\Tables\MelisPagePublishedTable',
			'MelisEngineTablePageSaved' => 'MelisEngine\Model\Tables\MelisPageSavedTable',
			'MelisEngineTablePageSeo' => 'MelisEngine\Model\Tables\MelisPageSeoTable',
		    'MelisEngineTableCmsLang' => 'MelisEngine\Model\Tables\MelisCmsLangTable',
		    'MelisEngineTableStyle' => 'MelisEngine\Model\Tables\MelisCmsStyleTable',
		    'MelisEngineTablePageStyle' => 'MelisEngine\Model\Tables\MelisPageStyleTable',
			'MelisEngineTablePageDefaultUrls' => 'MelisEngine\Model\Tables\MelisPageDefaultUrlsTable',
            'MelisEngineTableRobot' => 'MelisEngine\Model\Tables\MelisCmsSiteRobotTable',
            
		    'MelisEngineStyleService' => 'MelisEngine\Service\MelisEngineStyleService',
            'MelisPageService' => 'MelisEngine\Service\MelisPageService',
            'MelisTreeService' => 'MelisEngine\Service\MelisTreeService',
            'MelisEngineLangService' => 'MelisEngine\Service\MelisEngineLangService',
		),
        'factories' => array(
			'MelisEnginePage' => 'MelisEngine\Service\Factory\MelisPageServiceFactory',
			'MelisEngineTree' => 'MelisEngine\Service\Factory\MelisTreeServiceFactory',
		    'MelisSearch' => 'MelisEngine\Service\Factory\MelisSearchServiceFactory',
		    'MelisEngineGeneralService' => 'MelisEngine\Service\Factory\MelisEngineGeneralServiceFactory',
            'MelisEngineSendMail' => 'MelisEngine\Service\Factory\MelisEngineSendMailServiceFactory',
			'MelisEngineCacheSystem' => 'MelisEngine\Service\Factory\MelisEngineCacheSystemServiceFactory',
			'MelisEngineStyle' => 'MelisEngine\Service\Factory\MelisEngineStyleServiceFactory',
			'MelisEngineLang' => 'MelisEngine\Service\Factory\MelisEngineLangServiceFactory',

            'MelisEngine\Model\Tables\MelisCmsLangTable' => 'MelisEngine\Model\Tables\Factory\MelisCmsLangTableFactory',
            'MelisEngine\Model\Tables\MelisPageLangTable' => 'MelisEngine\Model\Tables\Factory\MelisCmsPageLangTableFactory',
            'MelisEngine\Model\Tables\MelisPageTreeTable' => 'MelisEngine\Model\Tables\Factory\MelisCmsPageTreeTableFactory',
            'MelisEngine\Model\Tables\MelisTemplateTable' => 'MelisEngine\Model\Tables\Factory\MelisCmsTemplateTableFactory',
            'MelisEngine\Model\Tables\MelisSiteTable' => 'MelisEngine\Model\Tables\Factory\MelisCmsSiteTableFactory',
            'MelisEngine\Model\Tables\MelisSiteDomainTable' => 'MelisEngine\Model\Tables\Factory\MelisCmsSiteDomainTableFactory',
            'MelisEngine\Model\Tables\MelisSite404Table' => 'MelisEngine\Model\Tables\Factory\MelisCmsSite404TableFactory',
            'MelisEngine\Model\Tables\MelisSite301Table' => 'MelisEngine\Model\Tables\Factory\MelisCmsSite301TableFactory',
            'MelisEngine\Model\Tables\MelisPagePublishedTable' => 'MelisEngine\Model\Tables\Factory\MelisCmsPagePublishedTableFactory',
            'MelisEngine\Model\Tables\MelisPageSavedTable' => 'MelisEngine\Model\Tables\Factory\MelisCmsPageSavedTableFactory',
            'MelisEngine\Model\Tables\MelisPageSeoTable' => 'MelisEngine\Model\Tables\Factory\MelisCmsPageSeoTableFactory',
            'MelisEngine\Model\Tables\MelisPlatformIdsTable' => 'MelisEngine\Model\Tables\Factory\MelisCmsPlatformIdsTableFactory',
            'MelisEngine\Model\Tables\MelisPageDefaultUrlsTable' => 'MelisEngine\Model\Tables\Factory\MelisCmsPageDefaultUrlsTableFactory',
            'MelisEngine\Model\Tables\MelisCmsStyleTable' => 'MelisEngine\Model\Tables\Factory\MelisCmsStyleTableFactory',
            'MelisEngine\Model\Tables\MelisPageStyleTable' => 'MelisEngine\Model\Tables\Factory\MelisPageStyleTableFactory',
            'MelisEngine\MelisPageColumns' => 'MelisEngine\Model\Tables\Factory\MelisCmsPageColumnsFactory',
            'MelisEngine\Model\Tables\MelisCmsSiteRobotTable' => 'MelisEngine\Model\Tables\Factory\MelisCmsSiteRobotTableFactory',
		),
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
        )
    ),
    'controllers' => array(
        'invokables' => array(
            'MelisEngine\Controller\MelisSetup' => 'MelisEngine\Controller\MelisSetupController',
            //updated installer
            'MelisEngine\Controller\MelisSetupPostDownload' => 'MelisEngine\Controller\MelisSetupPostDownloadController',
            'MelisEngine\Controller\Setup\MelisSetupPostUpdate'   => 'MelisEngine\Controller\MelisSetupPostUpdateController',
        ),
    ),
    'form_elements' => array(
        'factories' => array(
    		'MelisEnginePluginTemplateSelect' => 'MelisEngine\Form\Factory\PluginTemplateSelectFactory',
    		'MelisEngineSiteSelect'           => 'MelisEngine\Form\Factory\SitesSelectFactory',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'template_map' => array(
            'layout/layoutEngine'           => __DIR__ . '/../view/layout/layoutEngine.phtml',
            'melis-engine/index/index'  => __DIR__ . '/../view/melis-engine/render/index.phtml',
            'MelisEngine/emailLayout'          => __DIR__ . '/../view/layout/email-layout.phtml',
            'melis-engine/plugins/notemplate'  => __DIR__ . '/../view/melis-engine/plugins/notemplate.phtml',
            'melis-engine/plugins/noformtemplate'  => __DIR__ . '/../view/melis-engine/plugins/noformtemplate.phtml',
            'melis-engine/plugins/meliscontainer'  => __DIR__ . '/../view/melis-engine/plugins/default-melis-container-view.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    'caches' => array(
        'engine_page_services' => array( 
            'active' => true, // activate or deactivate Melis Cache for this conf
            'adapter' => array(
                'name'    => 'Memory',
                'options' => array('ttl' => 0, 'namespace' => 'melisengine'),
            ),
            'plugins' => array(
                'exception_handler' => array('throw_exceptions' => false),
            ),
            'ttls' => array(
                // add a specific ttl for a specific cache key
                // 'my_cache_key' => 60,
            )
        ),
        'engine_lang_services' => array(
            'active' => true, // activate or deactivate Melis Cache for this conf
            'adapter' => array(
                'name'    => 'Memory',
                'options' => array('ttl' => 0, 'namespace' => 'melisengine'),
            ),
            'plugins' => array(
                'exception_handler' => array('throw_exceptions' => false),
            ),
            'ttls' => array(
                // add a specific ttl for a specific cache key
                // 'my_cache_key' => 60,
            )
        ),
        'templating_plugins' => array( 
            'adapter' => array(
                'name'    => 'Memory',
                'options' => array('ttl' => 0, 'namespace' => 'templating_plugins'),
            ),
            'plugins' => array(
                'exception_handler' => array('throw_exceptions' => false),
            ),
        ),
        'meliscms_page' => array(
            'active' => true, // activate or deactivate Melis Cache for this conf
            'adapter' => array(
                'name'    => 'Filesystem',
                'options' => array(
                    'ttl' => 0, // 24hrs
                    'namespace' => 'meliscms_page',
                    'cache_dir' => $_SERVER['DOCUMENT_ROOT'] . '/../cache'
                ),
            ),
            'plugins' => array(
                'exception_handler' => array('throw_exceptions' => false),
                'Serializer'
            ),
            'ttls' => array(
                // add a specific ttl for a specific cache key (found via regexp)
                // 'my_cache_key' => 60,
            )
        ),
    ),
);