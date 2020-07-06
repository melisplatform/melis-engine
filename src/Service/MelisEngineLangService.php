<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Service;

use MelisEngine\Model\MelisPage;
use MelisEngine\Service\MelisEngineGeneralService;
use Zend\Session\Container;

class MelisEngineLangService extends MelisEngineGeneralService implements MelisEngineLangServiceInterface
{
    /**
     * This service gets all available languages
     */
    public function getAvailableLanguages()
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());

        // Sending service start event
        $arrayParameters = $this->sendEvent('melisengine_service_get_available_languages_start', $arrayParameters);

        // Service implementation start
        $langTable = $this->getServiceLocator()->get('MelisEngineTableCmsLang');
        $results = $langTable->fetchAll()->toArray();
        // Service implementation end

        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('melisengine_service_get_available_languages_end', $arrayParameters);

        return $arrayParameters['results'];
    }

    /**
     * This service searches a value for matching page name or page id
     *
     * @param int $langId language id
     *
     * @return array language locales
     */
    public function getLocaleByLangId($langId)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());

        // Sending service start event
        $arrayParameters = $this->sendEvent('melisengine_service_get_local_by_id_start', $arrayParameters);

        // Service implementation start
        $langTable = $this->getServiceLocator()->get('MelisEngineTableCmsLang');
        $result = $langTable->getEntryByField('lang_cms_id', $langId)->toArray();
        if(!isset($result[0]['lang_cms_locale'])) return null;
        $result = $result[0]['lang_cms_locale'];
        // Service implementation end

        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $result;
        // Sending service end event
        $arrayParameters = $this->sendEvent('melisengine_service_get_local_by_id_end', $arrayParameters);

        return $arrayParameters['results'];
    }

    /**
     * This service searches a value for matching page name or page id
     *
     * @param int $locale language locale
     *
     * @return array language
     */
    public function getLangByLocale($locale)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());

        // Sending service start event
        $arrayParameters = $this->sendEvent('melisengine_service_get_lang_by_local_start', $arrayParameters);

        // Service implementation start
        $langTable = $this->getServiceLocator()->get('MelisEngineTableCmsLang');
        $result = $langTable->getEntryByField('lang_cms_locale', $locale)->toArray();
        if(!isset($result[0]['lang_cms_locale'])) return null;
        $result = $result[0];
        // Service implementation end

        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $result;
        // Sending service end event
        $arrayParameters = $this->sendEvent('melisengine_service_get_lang_by_local_end', $arrayParameters);

        return $arrayParameters['results'];
    }

    /**
     * This service return the language id and its locale 
     * use for transating text fort
     *
     * @return array language
     */
    public function getSiteLanguage()
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());

        // Sending service start event
        $arrayParameters = $this->sendEvent('melisengine_service_get_site_lang_start', $arrayParameters);

        // Service implementation start
        $siteModule = getenv('MELIS_MODULE');
        $container = new Container('melisplugins');
        $config = $this->getServiceLocator()->get('config');

        $langId = $container['melis-plugins-lang-id'];
        $langLocale = $container['melis-plugins-lang-locale'];

        if (!empty($config['site'][$siteModule]['language']['language_id'])) 
            $langId = $config['site'][$siteModule]['language']['language_id'];

        if (!empty($config['site'][$siteModule]['language']['language_locale'])) 
            $langLocale = $config['site'][$siteModule]['language']['language_locale'];

        $result = array(
            'langId' => $langId,
            'langLocale' => $langLocale,
        );
        // Service implementation end

        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $result;
        // Sending service end event
        $arrayParameters = $this->sendEvent('melisengine_service_get_site_lang_end', $arrayParameters);

        return $arrayParameters['results'];
    }

    /**
     * @param $siteLangLocale
     * @return mixed
     */
    public function getLangDataByLangLocale($siteLangLocale)
    {
        //try to get config from cache
        $cacheKey = 'getLangDataByLangLocale_' . $siteLangLocale;
        $cacheConfig = 'engine_page_services';
        $melisEngineCacheSystem = $this->getServiceLocator()->get('MelisEngineCacheSystem');
        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
        if(!is_null($results)) return $results;

        $langCmsTbl = $this->getServiceLocator()->get('MelisEngineTableCmsLang');
        $langData = $langCmsTbl->getEntryByField('lang_cms_locale', $siteLangLocale)->current();

        $melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $langData);

        return $langData;
    }

    /**
     * Function to get lang data
     *
     * @param $langId
     * @return mixed
     */
    public function getLangDataById($langId)
    {
        //try to get config from cache
        $cacheKey = 'getLangDataById_' . $langId;
        $cacheConfig = 'engine_page_services';
        $melisEngineCacheSystem = $this->getServiceLocator()->get('MelisEngineCacheSystem');
        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
        if(!is_null($results)) return $results;

        $langCmsTbl = $this->getServiceLocator()->get('MelisEngineTableCmsLang');
        $langList = $langCmsTbl->getEntryById($langId)->toArray();

        $melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $langList);

        return $langList;
    }
}