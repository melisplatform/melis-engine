<?php
/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2019 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Service;

use MelisEngine\Service\MelisEngineGeneralService;

class MelisEngineSEOService extends MelisEngineGeneralService
{
    public function getSEOById($seoId)
    {
        if (empty($seoId))
			return null;
		
		// Retrieve cache version if front mode to avoid multiple calls
		$cacheKey = 'getSEOById' . $seoId;
		$cacheConfig = 'engine_memory_cache';
		$melisEngineCacheSystem = $this->serviceLocator->get('MelisEngineCacheSystem');
		$results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);

		if (!is_null($results))
			return $results; 
		
		$melisTablePageSeo = $this->getServiceLocator()->get('MelisEngineTablePageSeo');
		$pageSeo = $melisTablePageSeo->getEntryById($seoId);

		// Save cache key
		$melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $pageSeo);
		
		return $pageSeo;
    }
}