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

class MelisPageService extends MelisEngineGeneralService implements MelisPageServiceInterface
{
	/**
	 * This service gets all datas of a page
	 * 
	 * @param int $idPage The page id
	 * @param string $type published or saved, in case you want only published page (front) or both (back)
	 * 
	 */
	public function getDatasPage($idPage, $type = 'published')
	{
	    if (empty($idPage))
	        return null;
	    
	    // Retrieve cache version if front mode to avoid multiple calls
	    $cacheKey = 'getDatasPage_' . $idPage . '_' . $type;
	    $results = $this->getCacheServiceResults($cacheKey);
	    if (!empty($results))
	        return $results;
	    
		$melisPage = new MelisPage();
		$melisPage->setId($idPage);
		$melisPage->setType($type);
		
		$tablePageTree = $this->serviceLocator->get('MelisEngineTablePageTree');
		$melisPageTreePublished = $tablePageTree->getFullDatasPage($idPage, 'published');
		$datasPagePublished = $melisPageTreePublished->current();
		
		$tplId = 0;
		if ($type == 'published')
		{
			$melisPage->setMelisPageTree($datasPagePublished);
			if ($melisPageTreePublished->count() > 0)
				if (!empty($datasPagePublished->page_tpl_id))
					$tplId = $datasPagePublished->page_tpl_id;
		}
		else
		{
			$tablePageTreeSaved = $this->serviceLocator->get('MelisEngineTablePageTree');
			$melisPageTreeSaved = $tablePageTreeSaved->getFullDatasPage($idPage, 'saved');
			$datasPageSaved = $melisPageTreeSaved->current();
			
			if ($melisPageTreeSaved->count() > 0 && !empty($datasPageSaved->page_id))
			{
				$melisPage->setMelisPageTree($datasPageSaved);
				if (!empty($datasPageSaved->page_tpl_id))
					$tplId = $datasPageSaved->page_tpl_id;
			}
			else
			{
				$melisPage->setMelisPageTree($datasPagePublished);
				if ($melisPageTreePublished->count() > 0)
					if (!empty($datasPagePublished->page_tpl_id))
						$tplId = $datasPagePublished->page_tpl_id;
			}
		}

		if (!empty($tplId))
		{
			$tableTemplate = $this->serviceLocator->get('MelisEngineTableTemplate');
			$melisTemplateRes = $tableTemplate->getEntryById($tplId);
			$melisPage->setMelisTemplate($melisTemplateRes->current());
		}
		
		// Save cache key
		$this->setCacheServiceResults($cacheKey, $melisPage);
		
		return $melisPage;
	}
	
	/**
	 * This function searches a value for matching page name or page id
	 * 
	 * @param string $value The search value
	 * @param string $type page type
	 * @return array page ids
	 */
	public function searchPage($value, $type = 'published')
	{

	    $results = array();
	  
	    // Service implementation start
	    $pageTreeTable = $this->getServiceLocator()->get('MelisEngineTablePageTree');
	    $pages = $pageTreeTable->getPagesBySearchValue($value, $type);
	     
	    foreach($pages as $page){
	        $results[] = $page;
	    }

	    return $results;
	}
}