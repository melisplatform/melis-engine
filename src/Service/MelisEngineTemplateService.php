<?php
/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2019 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Service;

use MelisEngine\Service\MelisEngineGeneralService;

class MelisEngineTemplateService extends MelisEngineGeneralService
{
    public function getTemplate($tplId)
    {
        if (empty($tplId))
			return null;
		
		// Retrieve cache version if front mode to avoid multiple calls
		$cacheKey = 'getTemplate_' . $tplId;
		$cacheConfig = 'engine_memory_cache';
		$melisEngineCacheSystem = $this->serviceLocator->get('MelisEngineCacheSystem');
		$results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);

		if (!is_null($results))
			return $results; 
		
        $tplTable = $this->getServiceLocator()->get('MelisEngineTableTemplate');
        $tplData = $tplTable->getEntryById($tplId);

		// Save cache key
		$melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $tplData);
		
		return $tplData;
    }
}