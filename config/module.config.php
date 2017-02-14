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
        ),
    ),
    'translator' => array(
    	'locale' => 'en_EN',
	),
    'service_manager' => array(
		'invokables' => array(
			'MelisEngine\Service\MelisPageServiceInterface' => 'MelisEngine\Service\MelisPageService',
			'MelisEngine\Service\MelisTreeServiceInterface' => 'MelisEngine\Service\MelisTreeService',
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
		),
        'factories' => array(
			'MelisEnginePage' => 'MelisEngine\Service\Factory\MelisPageServiceFactory',
			'MelisEngineTree' => 'MelisEngine\Service\Factory\MelisTreeServiceFactory',
		    'MelisSearch' => 'MelisEngine\Service\Factory\MelisSearchServiceFactory',
		    'MelisEngineGeneralService' => 'MelisEngine\Service\Factory\MelisEngineGeneralServiceFactory',
            
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
            'MelisEngine\MelisPageColumns' => 'MelisEngine\Model\Tables\Factory\MelisCmsPageColumnsFactory',
		),
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
        )
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'template_map' => array(
            'layout/layoutEngine'           => __DIR__ . '/../view/layout/layoutEngine.phtml',
            'melis-engine/index/index'  => __DIR__ . '/../view/melis-engine/render/index.phtml',
            'melis-engine/plugins/notemplate'  => __DIR__ . '/../view/melis-engine/plugins/notemplate.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    'caches' => array(
        'engine_services' => array( 
            'adapter' => array(
                'name'    => 'Memory',
                'options' => array('ttl' => 0, 'namespace' => 'melisengine'),
            ),
            'plugins' => array(
                'exception_handler' => array('throw_exceptions' => false),
            ),
        ),
    ),
);