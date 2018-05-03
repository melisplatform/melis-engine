<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Service;

interface MelisEngineLangServiceInterface
{
    /**
     * This service gets all available languages
     */
    public function getAvailableLanguages();

    /**
     * This service searches a value for matching page name or page id
     *
     * @param int $langId language id
     *
     * @return array language locales
     */
    public function getLocaleByLangId($langId);

    /**
     * This service searches a value for matching page name or page id
     *
     * @param int $locale language locale
     *
     * @return array language
     */
    public function getLangByLocale($locale);
}