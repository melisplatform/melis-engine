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
}