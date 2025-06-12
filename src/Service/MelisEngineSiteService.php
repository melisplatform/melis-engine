<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Service;

/**
 * 
 * This service handles the generic service system of Melis.
 *
 */
class MelisEngineSiteService extends MelisEngineGeneralService
{

    /**
     * Get Site data by Site ID
     * 
     * @param $siteId
     * @return mixed
     */
    public function getSiteById($siteId)
    {
        //try to get config from cache
        $cacheKey = 'getSiteById_' . $siteId;
        $cacheConfig = 'engine_memory_cache';
        $melisEngineCacheSystem = $this->getServiceManager()->get('MelisEngineCacheSystem');
        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
        if(!is_null($results)) return $results;

        $siteTable = $this->getServiceManager()->get('MelisEngineTableSite');
        $siteData = $siteTable->getEntryById($siteId);

        $melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $siteData);

        return $siteData;
    }

    /**
     * @param $siteId
     * @return string
     */
    public function getSiteDNDRenderMode($siteId)
    {
        $mode = '';
        $siteTable = $this->getServiceManager()->get('MelisEngineTableSite');
        $siteData = $siteTable->getEntryById($siteId);
        if(!empty($siteData))
            $mode = $siteData->current()->site_dnd_render_mode;

        return $mode;
    }

    /**
     * @param $pageId
     * @return string
     */
    public function getSiteDNDRenderModeByPageId($pageId)
    {
        if(!empty($pageId)) {
            $treeService = $this->getServiceManager()->get('MelisEngineTree');
            $siteData = $treeService->getSiteByPageId($pageId);
            if (empty($siteData))
                $siteData = $treeService->getSiteByPageId($pageId, 'saved');

            return $this->getSiteDNDRenderMode($siteData->site_id);
        }
        return '';
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
        $treeService = $this->getServiceManager()->get('MelisEngineTree');
        $cacheDom = $treeService->cleanString($domain);
        $cacheDom = str_replace('.', '', $cacheDom);
        //try to get data from cache
        $cacheKey = 'getSiteDataByDomain_' . $cacheDom;
        $cacheConfig = 'engine_page_services';
        $melisEngineCacheSystem = $this->getServiceManager()->get('MelisEngineCacheSystem');
        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
        if(!is_null($results)) return $results;

        $donSrv = $this->getServiceManager()->get('MelisEngineSiteDomainService');
        $datasDomain = $donSrv->getDomainByDomainName($domain);

        if (!$datasDomain)
            return null;

        $siteId = $datasDomain->sdom_site_id;

        /**
         * Get site data
         */
        $siteData = $this->getSiteById($siteId)->current();

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
        $melisEngineCacheSystem = $this->getServiceManager()->get('MelisEngineCacheSystem');
        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
        if(!is_null($results)) return $results;

        $siteHomeTable = $this->getServiceManager()->get('MelisEngineTableCmsSiteHome');
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
        $melisEngineCacheSystem = $this->getServiceManager()->get('MelisEngineCacheSystem');
        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
        if(!is_null($results)) return $results;

        $siteDatas = $this->getSiteById($siteId)->current();
        $pageId = $siteDatas->site_main_page_id;

        $melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $pageId);

        return $pageId;
    }
}