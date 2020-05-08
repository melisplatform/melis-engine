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
class MelisEngineSiteService extends MelisEngineGeneralService
{

    public function getSiteById($siteId)
    {
        //try to get config from cache
        $cacheKey = 'getSiteById_' . $siteId;
        $cacheConfig = 'engine_memory_cache';
        $melisEngineCacheSystem = $this->getServiceLocator()->get('MelisEngineCacheSystem');
        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
        if(!is_null($results)) return $results;

        $siteTable = $this->getServiceLocator()->get('MelisEngineTableSite');
        $siteData = $siteTable->getEntryById($siteId);

        $melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $siteData);

        return $siteData;
    }

    /**
     * Function to get site data by domain
     *
     * @param $domain
     * @return mixed
     */
    public function getSiteDataByDomain($domain)
    {
        //clean domain name
        $treeService = $this->getServiceLocator()->get('MelisEngineTree');
        $cacheDom = $treeService->cleanString($domain);
        $cacheDom = str_replace('.', '', $cacheDom);
        //try to get data from cache
        $cacheKey = 'getSiteDataByDomain_' . $cacheDom;
        $cacheConfig = 'engine_page_services';
        $melisEngineCacheSystem = $this->getServiceLocator()->get('MelisEngineCacheSystem');
        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
        if(!is_null($results)) return $results;

        $melisTableDomain = $this->getServiceLocator()->get('MelisEngineTableSiteDomain');
        $datasDomain = $melisTableDomain->getEntryByField('sdom_domain', $domain)->current();
        $siteId = $datasDomain->sdom_site_id;

        /**
         * Get site data
         */
        $siteTable = $this->getServiceLocator()->get('MelisEngineTableSite');
        $siteData = $siteTable->getEntryById($siteId)->current();

        $melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $siteData);

        return $siteData;
    }

    /**
     * Function to get homoe page data
     *
     * @param $siteId
     * @param $langId
     * @return mixed
     */
    public function getHomePageBySiteIdAndLangId($siteId, $langId)
    {
        //try to get data from cache
        $cacheKey = 'getHomePageBySiteIdAndLangId_' . $siteId.'_'. $langId;
        $cacheConfig = 'engine_page_services';
        $melisEngineCacheSystem = $this->getServiceLocator()->get('MelisEngineCacheSystem');
        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
        if(!is_null($results)) return $results;

        $siteHomeTable = $this->getServiceLocator()->get('MelisEngineTableCmsSiteHome');
        $siteHomeData = $siteHomeTable->getHomePageBySiteIdAndLangId($siteId, $langId)->current();

        $melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $siteHomeData);

        return $siteHomeData;
    }

    /**
     * Function to get site main home page id
     *
     * @param $siteId
     * @return mixed
     */
    public function getSiteMainHomePageIdBySiteId($siteId)
    {
        //try to get data from cache
        $cacheKey = 'getSiteMainHomePageIdBySiteId_' . $siteId;
        $cacheConfig = 'engine_page_services';
        $melisEngineCacheSystem = $this->getServiceLocator()->get('MelisEngineCacheSystem');
        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
        if(!is_null($results)) return $results;

        $siteTbl = $this->getServiceLocator()->get('MelisEngineTableSite');
        $siteDatas = $siteTbl->getEntryById($siteId)->current();
        $pageId = $siteDatas->site_main_page_id;

        $melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $pageId);

        return $pageId;
    }
}