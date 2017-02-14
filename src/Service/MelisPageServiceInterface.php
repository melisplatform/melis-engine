<?php
	
/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Service;

interface MelisPageServiceInterface 
{
    /**
     * This service gets all datas of a page
     *
     * @param int $idPage The page id
     * @param string $type published or saved, in case you want only published page (front) or both (back)
     *
     */
	public function getDatasPage($idPage, $type = 'published');
	
	/**
	 * This service searches a value for matching page name or page id
	 *
	 * @param string $value The search value
	 * @param string $type page type
	 * 
	 * @return array page ids
	 */
	public function searchPage($value, $type = 'published');
}