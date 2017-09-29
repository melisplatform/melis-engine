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
	    $cacheConfig = 'engine_page_services';
		$melisEngineCacheSystem = $this->serviceLocator->get('MelisEngineCacheSystem');
	    $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
	    if (!empty($results)) return $results;
	    
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
		$melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $melisPage);
		
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
	
	/**
	 * This function return all Page languages version 
	 * 
	 * @param int $pageId, Page id
	 * 
	 * @return MelisPage[]||array
	 */
	public function getPageLanguageList($pageId)
	{
	    // Retrieving the list of Page languages
	    $pageLangTbl = $this->getServiceLocator()->get('MelisEngineTablePageLang');
	    $pageLang = $pageLangTbl->getEntryByField('plang_page_id', $pageId)->current();
	    
	    $pagesData = array();
	    
	    if (!empty($pageLang))
	    {
	        $pageInitialId = $pageLang->plang_page_id_initial;
	        
	        $pages = $pageLangTbl->getEntryByField('plang_page_id_initial', $pageInitialId);
	        
	        foreach ($pages As $val)
	        {
	            $pageData = $this->getDatasPage($val->plang_page_id);
	            
	            array_push($pagesData, $pageData);
	        }
	    }
	    
	    return $pagesData;
	}
	
	/**
	 * This function retrieve Page using pageid and langid
	 * 
	 * @param int $pageId, Page id
	 * @param int $langId, Id of the Cms language
	 * 
	 * @return MelisPage||array
	 */
	public function getPageLanguageById($pageId, $langId) 
	{
	    // Retrieving the list of Page languages
	    $pageLangTbl = $this->getServiceLocator()->get('MelisEngineTablePageLang');
	    $pageLang = $pageLangTbl->getPageLanguageById($pageId, $langId)->current();
	    
	    $pagesData = array();
	    
	    if (!empty($pageLang))
	    {
	        $pagesData = $this->getDatasPage($pageLang->plang_page_id);
	    }
	    
	    return $pagesData;
	}
}