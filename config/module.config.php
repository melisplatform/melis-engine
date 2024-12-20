<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2015 Melis Technology (http://www.melistechnology.com)
 *
 */

return [
    'router' => [
        'routes' => [
            'melis-backoffice' => [
                'type'    => 'Segment',
                'options' => [
                    'route'    => '/melis[/]',
                ],
                'child_routes' => [
                    'application-MelisEngine' => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => 'MelisEngine',
                            'defaults' => [
                                '__NAMESPACE__' => 'MelisEngine\Controller',
                                'controller'    => 'MelisSetup',
                                'action'        => 'setup-form',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'default' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route'    => '/[:controller[/:action]]',
                                    'constraints' => [
                                        'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                    ],
                                    'defaults' => [
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            /*
             * This route will handle the
             * alone setup of a module
             */
            'setup-melis-engine' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/MelisEngine',
                    'defaults' => [
                        '__NAMESPACE__' => 'MelisEngine\Controller',
                        'controller'    => 'MelisSetup',
                        'action'        => 'setup-form',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'default' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => [
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ],
                            'defaults' => [
                            ],
                        ],
                    ],
                    'setup' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/setup',
                            'defaults' => [
                                'controller' => 'MelisEngine\Controller\MelisSetup',
                                'action' => 'setup-form',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'service_manager' => [
        'invokables' => [
//            @TODO need to confirm whats the use of the following invokables
//            \MelisEngine\Service\MelisPageServiceInterface::class     => \MelisEngine\Service\MelisPageService::class,
//            \MelisEngine\Service\MelisTreeServiceInterface::class     => \MelisEngine\Service\MelisTreeService::class,
//            \MelisEngine\Service\MelisEngineSendMailInterface::class  => \MelisEngine\Service\MelisEngineSendMailService::class,
//            \MelisEngine\Service\MelisEngineStyleInterface::class     => \MelisEngine\Service\MelisEngineStyleService::class,
//            \MelisEngine\Service\MelisEngineLangInterface::class      => \MelisEngine\Service\MelisEngineLangService::class
        ],
        'factories' => [
            // Metadata 'melis_cms_page_saved'
            'MelisEngine\MelisPageColumns'  => \MelisEngine\Model\Tables\Factory\MelisCmsPageColumnsFactory::class,
        ],
        'aliases' => [
            // @TODO to be remove replaced by MelisGeneralService
            // 'MelisEngineGeneralService'           => \MelisCore\Service\MelisGeneralService::class,
            // Services
            'MelisGeneralService'               => \MelisCore\Service\MelisGeneralService::class,
            'MelisEnginePage'                   => \MelisEngine\Service\MelisPageService::class,
            'MelisEngineTree'                   => \MelisEngine\Service\MelisTreeService::class,
            'MelisSearch'                       => \MelisEngine\Service\MelisSearchService::class,
            'MelisEngineSendMail'               => \MelisEngine\Service\MelisEngineSendMailService::class,
            'MelisEngineCacheSystem'            => \MelisEngine\Service\MelisEngineCacheSystemService::class,
            'MelisEngineStyle'                  => \MelisEngine\Service\MelisEngineStyleService::class,
            'MelisEngineLang'                   => \MelisEngine\Service\MelisEngineLangService::class,
            'MelisGdprService'                  => \MelisEngine\Service\MelisGdprService::class,
            'MelisEngineComposer'               => \MelisEngine\Service\MelisEngineComposerService::class,
            'MelisEngineTemplateService'        => \MelisEngine\Service\MelisEngineTemplateService::class,
            'MelisEngineSEOService'             => \MelisEngine\Service\MelisEngineSEOService::class,
            'MelisEnginePageDefaultUrlsService' => \MelisEngine\Service\MelisEnginePageDefaultUrlsService::class,
            'MelisEngineSiteService'            => \MelisEngine\Service\MelisEngineSiteService::class,
            'MelisEngineSiteDomainService'      => \MelisEngine\Service\MelisEngineSiteDomainService::class,
            'MelisGdprAutoDeleteService'        => \MelisEngine\Service\MelisGdprAutoDeleteService::class,

            // Model tables
            'MelisCmsGdprTextsTable'            => \MelisEngine\Model\Tables\MelisCmsGdprTextsTable::class,
            'MelisEngineTableCmsLang'           => \MelisEngine\Model\Tables\MelisCmsLangTable::class,
            'MelisEngineTablePageLang'          => \MelisEngine\Model\Tables\MelisPageLangTable::class,
            'MelisEngineTablePageTree'          => \MelisEngine\Model\Tables\MelisPageTreeTable::class,
            'MelisEngineTableTemplate'          => \MelisEngine\Model\Tables\MelisTemplateTable::class,
            'MelisEngineTableSite'              => \MelisEngine\Model\Tables\MelisSiteTable::class,
            'MelisEngineTableSiteDomain'        => \MelisEngine\Model\Tables\MelisSiteDomainTable::class,
            'MelisEngineTableSite404'           => \MelisEngine\Model\Tables\MelisSite404Table::class,
            'MelisEngineTableSite301'           => \MelisEngine\Model\Tables\MelisSite301Table::class,
            'MelisEngineTablePagePublished'     => \MelisEngine\Model\Tables\MelisPagePublishedTable::class,
            'MelisEngineTablePageSaved'         => \MelisEngine\Model\Tables\MelisPageSavedTable::class,
            'MelisEngineTablePageSeo'           => \MelisEngine\Model\Tables\MelisPageSeoTable::class,
            'MelisEngineTablePlatformIds'       => \MelisEngine\Model\Tables\MelisPlatformIdsTable::class,
            'MelisEngineTablePageDefaultUrls'   => \MelisEngine\Model\Tables\MelisPageDefaultUrlsTable::class,
            'MelisEngineTableStyle'             => \MelisEngine\Model\Tables\MelisCmsStyleTable::class,
            'MelisEngineTablePageStyle'         => \MelisEngine\Model\Tables\MelisPageStyleTable::class,
            'MelisEngineTableRobot'             => \MelisEngine\Model\Tables\MelisCmsSiteRobotTable::class,
            'MelisEngineTableCmsSiteHome'       => \MelisEngine\Model\Tables\MelisCmsSiteHomeTable::class,
            'MelisEngineTableCmsSiteLangs'      => \MelisEngine\Model\Tables\MelisCmsSiteLangsTable::class,
            'MelisEngineTableCmsSiteConfig'     => \MelisEngine\Model\Tables\MelisCmsSiteConfigTable::class,
            'MelisSiteTranslationTable'         => \MelisEngine\Model\Tables\MelisSiteTranslationTable::class,
            'MelisSiteTranslationTextTable'     => \MelisEngine\Model\Tables\MelisSiteTranslationTextTable::class,
            'MelisCmsMiniTplCategoryTable'             => \MelisEngine\Model\Tables\MelisCmsMiniTplCategoryTable::class,
            'MelisCmsMiniTplCategoryTransTable'        => MelisEngine\Model\Tables\MelisCmsMiniTplCategoryTransTable::class,
            'MelisCmsMiniTplCategoryTemplateTable' => MelisEngine\Model\Tables\MelisCmsMiniTplCategoryTemplateTable::class,
            'MelisCmsMiniTplSiteCategoryTable'  => MelisEngine\Model\Tables\MelisCmsMiniTplSiteCategoryTable::class,
            'MelisGdprAutodeleteConfigTable'    => \MelisEngine\Model\Tables\MelisGdprAutoDeleteConfigTable::class,
            'MelisGdprDeleteEmailsSentTable'    => \MelisCore\Model\Tables\MelisGdprDeleteEmailsSentTable::class,
            'MelisEngineTableCmsSiteBundle'    => \MelisEngine\Model\Tables\MelisCmsSiteBundleTable::class,
            // Alias redeclared
            // **This not neccessary but its already used from modules**
            'MelisEngineStyleService'           => \MelisEngine\Service\MelisEngineStyleService::class,
            'MelisPageService'                  => \MelisEngine\Service\MelisPageService::class,
            'MelisTreeService'                  => \MelisEngine\Service\MelisTreeService::class,
            'MelisEngineLangService'            => \MelisEngine\Service\MelisEngineLangService::class,
            'MelisEngineTableFlaggedTemplate'    => \MelisEngine\Model\Tables\MelisCmsMiniTplFlaggedTemplateTable::class,
        ]
    ],
    'controllers' => [
        'invokables' => [
            'MelisEngine\Controller\MelisSetup'                     => \MelisEngine\Controller\MelisSetupController::class,
            //updated installer
            'MelisEngine\Controller\MelisSetupPostDownload'         => \MelisEngine\Controller\MelisSetupPostDownloadController::class,
            'MelisEngine\Controller\Setup\MelisSetupPostUpdate'     => \MelisEngine\Controller\MelisSetupPostUpdateController::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            'MelisEnginePluginTemplateSelect' => \MelisEngine\Form\Factory\PluginTemplateSelectFactory::class,
            'MelisEngineSiteSelect'           => \MelisEngine\Form\Factory\SitesSelectFactory::class,
        ],
    ],
    'view_manager' => [
        'template_map' => [
            'layout/layoutEngine'                   => __DIR__ . '/../view/layout/layoutEngine.phtml',
            'melis-engine/index/index'              => __DIR__ . '/../view/melis-engine/render/index.phtml',
            'MelisEngine/emailLayout'               => __DIR__ . '/../view/layout/email-layout.phtml',
            'melis-engine/plugins/notemplate'       => __DIR__ . '/../view/melis-engine/plugins/notemplate.phtml',
            'melis-engine/plugins/noformtemplate'   => __DIR__ . '/../view/melis-engine/plugins/noformtemplate.phtml',
            'melis-engine/plugins/meliscontainer'   => __DIR__ . '/../view/melis-engine/plugins/default-melis-container-view.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
    'caches' => [
        'engine_memory_cache' => [ 
            'active' => true, // activate or deactivate Melis Cache for this conf
            'adapter' =>'Memory',
            'options' => ['ttl' => 0, 'namespace' => 'engine_memory_cache'],
            'plugins' => [
                [
                    'name' => 'exception_handler',
                    'options' => [
                        'throw_exceptions' => false
                    ],
                ]
            ],
            'ttls' => [
                // add a specific ttl for a specific cache key
                // 'my_cache_key' => 60,
            ]
        ],
        'engine_file_cache' => [
            'active' => true, // activate or deactivate Melis Cache for this conf
            'adapter' => 'Filesystem',
            'options' => [
                'ttl' => 0, // 24hrs
                'namespace' => 'meliscms_page',
                'cache_dir' => $_SERVER['DOCUMENT_ROOT'] . '/../cache'
            ],
            'plugins' => [
                [
                    'name' => 'exception_handler',
                    'options' => [
                        'throw_exceptions' => false,
                    ]
                ],
                [
                    'name' => 'Serializer'
                ]
            ],
            'ttls' => [
                // add a specific ttl for a specific cache key (found via regexp)
                // 'my_cache_key' => 60,
            ]
        ],
        'engine_page_services' => [ 
            'active' => true, // activate or deactivate Melis Cache for this conf
            'adapter' => \Laminas\Cache\Storage\Adapter\Memory::class,
            'options' => ['ttl' => 0, 'namespace' => 'melisengine'],
            'plugins' => [
                [
                    'name' => 'exception_handler',
                    'options' => [
                        'throw_exceptions' => false,
                    ]
                ]
            ],
            'ttls' => [
                // add a specific ttl for a specific cache key
                // 'my_cache_key' => 60,
            ]
        ],
        'engine_lang_services' => [
            'active' => true, // activate or deactivate Melis Cache for this conf,
            'adapter' => \Laminas\Cache\Storage\Adapter\Memory::class,
            'options' => ['ttl' => 0, 'namespace' => 'melisengine'],
            'plugins' => [
                [
                    'name' => 'exception_handler',
                    'options' => [
                        'throw_exceptions' => false,
                    ],
                ],
            ],
            'ttls' => [
                // add a specific ttl for a specific cache key
                // 'my_cache_key' => 60,
            ]
        ],
        'templating_plugins' => [
            'adapter' => \Laminas\Cache\Storage\Adapter\Memory::class,
            'options' => ['ttl' => 0, 'namespace' => 'templating_plugins'],
            'plugins' => [
                [
                    'name' => 'exception_handler',
                    'options' => [
                        'throw_exceptions' => false,
                    ],
                ],
            ],
        ],
        'meliscms_page' => [
            'active' => true, // activate or deactivate Melis Cache for this conf
            'adapter' => \Laminas\Cache\Storage\Adapter\Filesystem::class,
            'options' => [
                'ttl' => 0, // 24hrs
                'namespace' => 'meliscms_page',
                'cache_dir' => $_SERVER['DOCUMENT_ROOT'] . '/../cache'
            ],
            'plugins' => [
                [
                    'name' => 'exception_handler',
                    'options' => [
                        'throw_exceptions' => false,
                    ],
                ],
                [
                    'name' => 'Serializer'
                ]
            ],
            'ttls' => [
                // add a specific ttl for a specific cache key (found via regexp)
                // 'my_cache_key' => 60,
            ]
        ],
    ],
];