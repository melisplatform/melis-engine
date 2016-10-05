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
			'MelisEnginePage' => 'MelisEngine\Service\MelisPageService',
			'MelisEngineTree' => 'MelisEngine\Service\MelisTreeService',
			'MelisEngineTablePlatformIds' => 'MelisEngine\Model\Tables\MelisPlatformIdsTable',
			'MelisEngineTableTemplate' => 'MelisEngine\Model\Tables\MelisTemplateTable',
			'MelisEngineTablePageLang' => 'MelisEngine\Model\Tables\MelisPageLangTable',
			'MelisEngineTablePageTree' => 'MelisEngine\Model\Tables\MelisPageTreeTable',
			'MelisEngineTableSite' => 'MelisEngine\Model\Tables\MelisSiteTable',
			'MelisEngineTableSiteDomain' => 'MelisEngine\Model\Tables\MelisSiteDomainTable',
			'MelisEngineTableSite404' => 'MelisEngine\Model\Tables\MelisSite404Table',
			'MelisEngineTablePagePublished' => 'MelisEngine\Model\Tables\MelisPagePublishedTable',
			'MelisEngineTablePageSaved' => 'MelisEngine\Model\Tables\MelisPageSavedTable',
			'MelisEngineTablePageSeo' => 'MelisEngine\Model\Tables\MelisPageSeoTable',
		    'MelisEngineTableCmsLang' => 'MelisEngine\Model\Tables\MelisCmsLangTable',
		    'MelisSearch' => 'MelisEngine\Service\MelisSearchService',
		),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'template_map' => array(
            'layout/layoutEngine'           => __DIR__ . '/../view/layout/layoutEngine.phtml',
            'melis-engine/index/index'  => __DIR__ . '/../view/melis-engine/render/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);
