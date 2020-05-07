<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;

/**
 * 
 * This service handles the generic service system of Melis.
 *
 */
class MelisEngineSiteDomainService extends MelisEngineGeneralService
{
    /**
     * @param $domain
     * @return mixed
     */
	public function getDomainByDomainName($domain)
    {
        //clean domain name
        $treeService = $this->getServiceLocator()->get('MelisEngineTree');
        $cacheDom = $treeService->cleanString($domain);
        $cacheDom = str_replace('.', '', $cacheDom);

        //try to get config from cache
        $cacheKey = 'getSiteConfigByPageId_' . $cacheDom;
        $cacheConfig = 'engine_page_services';
        $melisEngineCacheSystem = $this->getServiceLocator()->get('MelisEngineCacheSystem');
        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
        if(!is_null($results)) return $results;

        $melisTableDomain = $this->getServiceLocator()->get('MelisEngineTableSiteDomain');
        $datasDomain = $melisTableDomain->getEntryByField('sdom_domain', $domain)->current();

        $melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $datasDomain);

        return $datasDomain;
    }
}