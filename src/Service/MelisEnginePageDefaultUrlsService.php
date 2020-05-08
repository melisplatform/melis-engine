<?php
/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2019 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Service;

use MelisEngine\Service\MelisEngineGeneralService;

class MelisEnginePageDefaultUrlsService extends MelisEngineGeneralService
{
    public function getPageDefaultUrl($pageId)
    {
        if (empty($pageId))
			return null;
		
		// Retrieve cache version if front mode to avoid multiple calls
		$cacheKey = 'getTemplate_' . $pageId;
		$cacheConfig = 'engine_memory_cache';
		$melisEngineCacheSystem = $this->serviceLocator->get('MelisEngineCacheSystem');
		$results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);

		if (!is_null($results))
			return $results; 
		
        $pageDefaultUrlsTable = $this->getServiceLocator()->get('MelisEngineTablePageDefaultUrls');
        $tplData = $pageDefaultUrlsTable->getEntryById($pageId)->toArray();

		// Save cache key
		$melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $tplData);
		
		return $tplData;
    }
}