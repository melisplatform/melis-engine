<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Service;

use MelisCore\Service\MelisGeneralService;
use MelisEngine\Model\MelisPage;

class MelisTreeService extends MelisGeneralService implements MelisTreeServiceInterface
{
	/**
	 * This service gets the children pages of a specific page
	 * 
	 * @param int $idPage The page id
	 * @param int $publishedOnly To retrieve only published pages or also saved version / unpublished
	 * 
	 */
	public function getPageChildren($idPage, $publishedOnly = 0)
	{
		// Retrieve cache version if front mode to avoid multiple calls
		$cacheKey = 'getPageChildren_' . $idPage . '_' . $publishedOnly;
		$cacheConfig = 'engine_page_services';
		$melisEngineCacheSystem = $this->getServiceManager()->get('MelisEngineCacheSystem');
		$results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);

		if (!is_null($results))
			return $results; 

		$tablePageTree = $this->getServiceManager()->get('MelisEngineTablePageTree');
		$pages = $tablePageTree->getPageChildrenByidPage($idPage, $publishedOnly)->toArray();

		// Save cache key
		$melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $pages);

		return $pages;
	}

	public function getAllPages($idPage)
	{
		$pages = [];
		$children = $this->getPageChildren($idPage);

		// print_r($children);

		foreach($children as $idx => $child) {

			if($child['tree_father_page_id'] == '-1') {
				$pages[$idx] = $child;
			}
			else {
				$pages['children'][$idx] = array_merge($child, $this->getAllPages($child['tree_page_id']));
			}
		}

		return $pages;
	}
	
	/**
	 * Gets the father page of a specific page
	 * 
	 * @param int $idPage
	 * 
	 */
	public function getPageFather($idPage, $type = 'published')
	{
		if (empty($idPage))
			return null;
		
		// Retrieve cache version if front mode to avoid multiple calls
		$cacheKey = 'getPageFather_' . $idPage;
		$cacheConfig = 'engine_page_services';
		$melisEngineCacheSystem = $this->getServiceManager()->get('MelisEngineCacheSystem');
		$results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
		if (!empty($results)) return $results;
			
		$tablePageTree = $this->getServiceManager()->get('MelisEngineTablePageTree');
		$datasPage = $tablePageTree->getFatherPageById($idPage, $type);

		// Save cache key
		$melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $datasPage);
		
		return $datasPage;
	}

	/**
	 * Gets the breadcromb of pages as an array
	 * 
	 * @param int $idPage The id page to start from
	 * @param int $typeLinkOnly Get only pages with a type LINK for menu
	 * @param boolean $allPages Brings it all 
	 * @return MelisPage[] Array of Melis Pages
	 * 
	 */
	public function getPageBreadcrumb($idPage, $typeLinkOnly = 1, $allPages = true)
	{
		if (empty($idPage))
			return null;
		
		// Retrieve cache version if front mode to avoid multiple calls
		$cacheKey = 'getPageBreadcrumb_' . $idPage . '_' . $typeLinkOnly . '_' . $allPages;
		$cacheConfig = 'engine_page_services';
		$melisEngineCacheSystem = $this->getServiceManager()->get('MelisEngineCacheSystem');
		$results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
		if (!empty($results)) return $results;
			
		$results = array();
		$tmp = $idPage;
		$melisPage = $this->getServiceManager()->get('MelisEnginePage');

		
		$datasPageRes = $melisPage->getDatasPage($idPage);
		$datasPageTreeRes = $datasPageRes->getMelisPageTree();
		
		if (!empty($datasPageTreeRes))
		{
			if ($datasPageTreeRes->page_status == 1 || $allPages)
			{
				if ($typeLinkOnly && $datasPageTreeRes->page_menu != 'NONE')
					array_push($results, $datasPageTreeRes);
				if (!$typeLinkOnly)
					array_push($results, $datasPageTreeRes);
			}
		}
		else
			return array();
	
		while ($tmp != -1)
		{
			$datasPageFatherRes = $this->getPageFather($tmp);
			$datas = $datasPageFatherRes->current();
				
			if (!empty($datas))
			{
				$tmp = $datas->tree_father_page_id;
				unset($datas->tree_page_id);
				unset($datas->tree_father_page_id);
				unset($datas->tree_page_order);
				$datas->tree_page_id = $tmp;
				if ($datasPageTreeRes->page_status == 1|| $allPages)
				{
					if ($typeLinkOnly && $datas->page_menu != 'NONE')
						array_push($results, $datas);
					if (!$typeLinkOnly)
						array_push($results, $datas);
				}
			}
			else
				break;
		}
	
		krsort($results);
		
		// Save cache key
		$melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $results);
		
		return $results;
	}
	
	/**
	 * Returns the link of a page, MelisUrl or specific SEO
	 * 
	 * @param int $idPage The page id for the link
	 * @param boolean $absolute If true, returns link with domain
	 * 
	 */ 
	public function getPageLink($idPage, $absolute = false)
	{
		if (empty($idPage))
			return null;

		// Retrieve cache version if front mode to avoid multiple calls
		$cacheKey = 'getPageLink_' . $idPage . '_' . $absolute;
		$cacheConfig = 'engine_page_services';
		$melisEngineCacheSystem = $this->getServiceManager()->get('MelisEngineCacheSystem');
		$results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
		if (!is_null($results)) return $results;

		// Get the already generated link from the DB if possible    
		$link = '';
		$pageDefaultUrlsSrv = $this->getServiceManager()->get('MelisEnginePageDefaultUrlsService');
		if ($this->getRenderMode() == 'front')
		{
			$defaultUrls = $pageDefaultUrlsSrv->getPageDefaultUrl($idPage);
			if (!empty($defaultUrls))
			{
				if (count($defaultUrls) > 0)
				{
					$link = $defaultUrls[0]['purl_page_url'];
				}
			}
		}

		// if nothing found in DB, then let's generate
		if ($link == '')
		{
			// Generate real one
			
			// Check for SEO URL first
			$seoUrl = '';
			$melisPage = $this->getServiceManager()->get('MelisEnginePage');
			$datasPageRes = $melisPage->getDatasPage($idPage);
			$datasPageTreeRes = $datasPageRes->getMelisPageTree();
			
			if ($datasPageTreeRes && !empty($datasPageTreeRes->pseo_url))
			{
				$seoUrl = $datasPageTreeRes->pseo_url;
				if (substr($seoUrl, 0, 1) != '/')
					$seoUrl = '/' . $seoUrl;
			}

			if ($seoUrl == '')
			{
				/**
				 * SITE V2 UPDATES
				 *
				 * This will check the site_opt_lang_url of the site
				 * to determine whether the url will be modified to
				 * add the lang locale on the url
				 */
				$siteLangOpt = $this->getSiteLangUrlOptByPageId($idPage);
				$seoUrl = $siteLangOpt['siteLangOptVal'];
				/**
				 * END V2 UPDATES
				 */
				// First let's see if page is the homepage one ( / no id following for url)
				$datasSite = $this->getSiteByPageId($idPage);
				if (!empty($datasSite) && $datasSite->site_main_page_id == $idPage)
				{
					$seoUrl = (!empty($seoUrl)) ? $seoUrl : '/';
				}
				else
				{
					// if not, construct a classic Melis URL /..../..../id/xx
					$datasPage = $this->getPageBreadcrumb($idPage);

					$seoUrl .= '/';
					foreach ($datasPage as $page)
					{
						if (!empty($datasSite) && $datasSite->site_main_page_id == $page->page_id)
							continue;

							$namePage = $page->page_name;
	
						$seoUrl .= $namePage . '/';
					}
					$seoUrl .= 'id/' . $idPage;
				}
			}

			$link = $this->cleanLink($seoUrl);

			$tablePageDefaultUrls = $this->getServiceManager()->get('MelisEngineTablePageDefaultUrls');
			$tablePageDefaultUrls->save(
				array(
					'purl_page_id' => $idPage,
					'purl_page_url' => $link
				),
				$idPage
			);
		}
			
		$router = $this->getServiceManager()->get('router');
		$request = $this->getServiceManager()->get('request');
		$routeMatch = $router->match($request);

		$idversion = null;
		if (!empty($routeMatch)){
			$idversion = $routeMatch->getParam('idversion');
		}

		if ($absolute || !empty($idversion))
		{
			$host = $this->getDomainByPageId($idPage);
			$link = $host . $link;
		}
		// Save cache key
		$melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $link);

		return $link;
	}

	/**
	 * Get the home page url of the
	 * given page id
	 *
	 * @param $idPage
	 * @param $absolute
	 * @return string|string[]|null
	 */
	public function getHomePageLink($idPage, $absolute = false)
	{
		// Retrieve cache version if front mode to avoid multiple calls
		$cacheKey = 'getHomePageLink_' . $idPage . '_' . $absolute;
		$cacheConfig = 'engine_page_services';
		$melisEngineCacheSystem = $this->getServiceManager()->get('MelisEngineCacheSystem');
		$results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
		if (!is_null($results)) return $results;

		/**
		 * prepare tables / services
		 */
		$siteHomeTable = $this->getServiceManager()->get('MelisEngineTableCmsSiteHome');
		$cmsPageLang = $this->getServiceManager()->get('MelisEngineTablePageLang');
		/**
		 * Get the site information using the given
		 * page id
		 *
		 * First let's try to get the page information
		 * in the page published table
		 */
		$siteData = $this->getSiteByPageId($idPage);
		/**
		 * If $siteData is still empty,
		 * then we get the page information
		 * in the page saved table
		 */
		if(empty($siteData)) {
			$melisPage = $this->getServiceManager()->get('MelisEnginePage');
			$datasPage = $melisPage->getDatasPage($idPage, 'saved');
			$datasTemplate = $datasPage->getMelisTemplate();
			if (!empty($datasTemplate) && !empty($datasTemplate->tpl_site_id))
			{
				$melisEngineTableSite = $this->getServiceManager()->get('MelisEngineTableSite');
				$siteData = $melisEngineTableSite->getSiteById($datasTemplate->tpl_site_id, getenv('MELIS_PLATFORM'));
				if ($siteData)
				{
					$siteData = $siteData->current();
				}
			}
		}

		$siteId = $siteData->site_id;
		$siteDefaultMainPage = $siteData->site_main_page_id;
		/**
		 * get page language
		 */
		$pageLangId = 0;
		$pageLang = $cmsPageLang->getEntryByField('plang_page_id', $idPage)->toArray();
		if(!empty($pageLang)){
			$pageLangId = $pageLang[0]['plang_lang_id'];
		}
		/**
		 * get site home page data
		 */
		$siteHomeData = $siteHomeTable->getHomePageBySiteIdAndLangId($siteId, $pageLangId)->toArray();
		$siteHomePageId = (!empty($siteHomeData)) ? $siteHomeData[0]['shome_page_id'] : $siteDefaultMainPage;

		$link = $this->getPageLink($siteHomePageId, $absolute);

		// Save cache key
		$melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $link);

		return $link;
	}

	/**
	 * Get the url language version of the page
	 *
	 * @param $idPage
	 * @param $locale
	 * @param $absolute
	 * @return string|string[]|null
	 */
	public function getPageLinkByLocale($idPage, $locale, $absolute = false)
	{
		$pageLocaleVersionId = '';
		/**
		 * Get the lang id of the given locale
		 */
		$langId = '';
		$langCmsTbl = $this->getServiceManager()->get('MelisEngineTableCmsLang');
		$langData = $langCmsTbl->getEntryByField('lang_cms_locale', $locale)->current();
		if(!empty($langData)){
			$langId = $langData->lang_cms_id;
		}
		/**
		 * Get the page id of given locale
		 * to get the url using the page id
		 */
		$pageTable = $this->getServiceManager()->get('MelisEngineTablePageLang');
		$pageRel = $pageTable->getPageRelationshipById($idPage)->toArray();
		if(!empty($pageRel)){
			foreach($pageRel as $key => $val){
				if($val['plang_lang_id'] == $langId){
					$pageLocaleVersionId = $val['plang_page_id'];
				}
			}
		}
		/**
		 * Get the link
		 */
		$link = $this->getPageLink($pageLocaleVersionId, $absolute);
		return $link;
	}

	/**
	 * @param $idPage
	 * @return mixed
	 */
	public function getSiteLangUrlOptByPageId($idPage)
	{
		// Retrieve cache version if front mode to avoid multiple calls
		$cacheKey = 'getSiteLangUrlOptByPageId_' . $idPage;
		$cacheConfig = 'engine_page_services';
		$melisEngineCacheSystem = $this->getServiceManager()->get('MelisEngineCacheSystem');
		$results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
		if (!is_null($results)) return $results;

		$datasSite = $this->getSiteByPageId($idPage);
		$siteLangOptVal = '';
		$result = [
			'siteLangOpt' => null,
			'siteId' => null,
			'siteLangOptVal' => $siteLangOptVal,
		];
		//make sure that site_opt_lang_url is exit in the site table
		if(!empty($datasSite->site_opt_lang_url)) {
			$result['siteLangOpt'] = $datasSite->site_opt_lang_url;
			$result['siteId'] = $datasSite->site_id;
			//check if we are going to add lang locale to the url
			if ($datasSite->site_opt_lang_url == 2) {
				//get the page language id from cms page lang
				$cmsPageLang = $this->getServiceManager()->get('MelisEngineTablePageLang');
				$pageLang = $cmsPageLang->getEntryByField('plang_page_id', $idPage)->current();
				if (!empty($pageLang)) {
					$pageLangId = $pageLang->plang_lang_id;
					//get the cms language locale to add on the url
					$langCmsTbl = $this->getServiceManager()->get('MelisEngineTableCmsLang');
					$langData = $langCmsTbl->getEntryById($pageLangId)->current();
					if (!empty($langData)) {
						$langLocale = explode('_', $langData->lang_cms_locale);
						//add the lang locale to the url
						$siteLangOptVal = '/' . $langLocale[0];
					}else{
						$siteLangOptVal = '';
					}
				}
			}
			$result['siteLangOptVal'] = $siteLangOptVal;
		}

		// Save cache key
		$melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $result);

		return $result;
	}
	
	/**
	 * Clean strings from special characters
	 * 
	 * @param string $str
	 */ 
	public function cleanString($str)
	{
		$str = preg_replace("/[áàâãªä]/u", "a", $str);
		$str = preg_replace("/[ÁÀÂÃÄ]/u", "A", $str);
		$str = preg_replace("/[ÍÌÎÏ]/u", "I", $str);
		$str = preg_replace("/[íìîï]/u", "i", $str);
		$str = preg_replace("/[éèêë]/u", "e", $str);
		$str = preg_replace("/[ÉÈÊË]/u", "E", $str);
		$str = preg_replace("/[óòôõºö]/u", "o", $str);
		$str = preg_replace("/[ÓÒÔÕÖ]/u", "O", $str);
		$str = preg_replace("/[úùûü]/u", "u", $str);
		$str = preg_replace("/[ÚÙÛÜ]/u", "U", $str);
		$str = preg_replace("/[’‘‹›‚]/u", "'", $str);
		$str = preg_replace("/[“”«»„]/u", '"', $str);
		$str = str_replace("–", "-", $str);
		$str = str_replace(" ", " ", $str);
		$str = str_replace("ç", "c", $str);
		$str = str_replace("Ç", "C", $str);
		$str = str_replace("ñ", "n", $str);
		$str = str_replace("Ñ", "N", $str);
		$str = str_replace("'", "-", $str);
			$str = str_replace("’", "-", $str);
		
		$trans = get_html_translation_table(HTML_ENTITIES);
		$trans[chr(130)] = '&sbquo;';    // Single Low-9 Quotation Mark
		$trans[chr(131)] = '&fnof;';    // Latin Small Letter F With Hook
		$trans[chr(132)] = '&bdquo;';    // Double Low-9 Quotation Mark
		$trans[chr(133)] = '&hellip;';    // Horizontal Ellipsis
		$trans[chr(134)] = '&dagger;';    // Dagger
		$trans[chr(135)] = '&Dagger;';    // Double Dagger
		$trans[chr(136)] = '&circ;';    // Modifier Letter Circumflex Accent
		$trans[chr(137)] = '&permil;';    // Per Mille Sign
		$trans[chr(138)] = '&Scaron;';    // Latin Capital Letter S With Caron
		$trans[chr(139)] = '&lsaquo;';    // Single Left-Pointing Angle Quotation Mark
		$trans[chr(140)] = '&OElig;';    // Latin Capital Ligature OE
		$trans[chr(145)] = '&lsquo;';    // Left Single Quotation Mark
		$trans[chr(146)] = '&rsquo;';    // Right Single Quotation Mark
		$trans[chr(147)] = '&ldquo;';    // Left Double Quotation Mark
		$trans[chr(148)] = '&rdquo;';    // Right Double Quotation Mark
		$trans[chr(149)] = '&bull;';    // Bullet
		$trans[chr(150)] = '&ndash;';    // En Dash
		$trans[chr(151)] = '&mdash;';    // Em Dash
		$trans[chr(152)] = '&tilde;';    // Small Tilde
		$trans[chr(153)] = '&trade;';    // Trade Mark Sign
		$trans[chr(154)] = '&scaron;';    // Latin Small Letter S With Caron
		$trans[chr(155)] = '&rsaquo;';    // Single Right-Pointing Angle Quotation Mark
		$trans[chr(156)] = '&oelig;';    // Latin Small Ligature OE
		$trans[chr(159)] = '&Yuml;';    // Latin Capital Letter Y With Diaeresis
		$trans['euro'] = '&euro;';    // euro currency symbol
		ksort($trans);
		
		foreach ($trans as $k => $v) {
			$str = str_replace($v, $k, $str);
		}
		
		$str = strip_tags($str);
		$str = html_entity_decode($str);
		$str = preg_replace('/[^(\x20-\x7F)]*/','', $str);
		$targets=array('\r\n', '\n', '\r', '\t');
		$results=array(" ", " ", " ", "");
		$str = str_replace($targets, $results, $str);
		
		return ($str);
	}

	/**
		* Cleans a link to allow only good characters
		* 
		* @param string $link
		*/
	public function cleanLink($link)
	{
		$link = strtolower(preg_replace(
					array('#[\\s-]+#', '#[^A-Za-z0-9/ -]+#'),
					array('-', ''),
					$this->cleanString(urldecode($link))
		));

		$link = preg_replace('/\/+/', '/', $link);
		$link = preg_replace('/-+/', '-', $link);
		
		return $link;
	}
	
	/**
		* Gets the domain as define in site table for a page id
		* 
		* @param int $idPage
		* @return string The domain with the scheme
		*/
	public function getDomainByPageId($idPage)
	{
		if (empty($idPage))
			return null;
		
		// Retrieve cache version if front mode to avoid multiple calls
		$cacheKey = 'getDomainByPageId_' . $idPage;
		$cacheConfig = 'engine_page_services';
		$melisEngineCacheSystem = $this->getServiceManager()->get('MelisEngineCacheSystem');
		$results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
		if (!empty($results)) return $results;
			
		$domainStr = '';
		$melisPage = $this->getServiceManager()->get('MelisEnginePage');
		$datasPage = $melisPage->getDatasPage($idPage);
		$datasTemplate = $datasPage->getMelisTemplate();
		if (!empty($datasTemplate) && !empty($datasTemplate->tpl_site_id))
		{
			$melisEngineTableSite = $this->getServiceManager()->get('MelisEngineTableSite');
			$datasSite = $melisEngineTableSite->getSiteById($datasTemplate->tpl_site_id, getenv('MELIS_PLATFORM'));
			if ($datasSite)
			{
				$datasSite = $datasSite->current();
				if (!empty($datasSite))
				{
					$scheme = 'http';
					if (!empty($datasSite->sdom_scheme))
						$scheme = $datasSite->sdom_scheme;
					
					$domain = $datasSite->sdom_domain;
					
					if ($domain != '')
						$domainStr = $scheme . '://' . $domain;
				}
			}
		}

		// Save cache key
		$melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $domainStr);
		
		return $domainStr;
	}
	
	/**
		* Gets the site object of a page id
		* 
		* @param int $idPage
		*/
	public function getSiteByPageId($idPage, $type = 'published')
	{
		if (empty($idPage))
			return null;

		// Retrieve cache version if front mode to avoid multiple calls
		$cacheKey = 'getSiteByPageId_' . $idPage;
		$cacheConfig = 'engine_page_services';
		$melisEngineCacheSystem = $this->getServiceManager()->get('MelisEngineCacheSystem');
		$results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
		if (!empty($results)) return $results;
		
		$datasSite = null;
		$melisPage = $this->getServiceManager()->get('MelisEnginePage');
		$datasPage = $melisPage->getDatasPage($idPage, $type);
		$datasTemplate = $datasPage->getMelisTemplate();

		if (!empty($datasTemplate) && !empty($datasTemplate->tpl_site_id))
		{
			$melisEngineTableSite = $this->getServiceManager()->get('MelisEngineTableSite');
			$datasSite = $melisEngineTableSite->getSiteById($datasTemplate->tpl_site_id, getenv('MELIS_PLATFORM'));
			if ($datasSite)
			{
				$datasSite = $datasSite->current();
			}
		}

		// Save cache key
		$melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $datasSite);
		
		return $datasSite;
	}
	
	/**
		* Gets the previous and next page for a specific page in the treeview
		* 
		* @param int $idPage The page id
		* @param int $publishedOnly Only active pages will be taken in consideration
		*/
	public function getPrevNextPage($idPage, $publishedOnly = 1) {
	
		$output = array(
			'prev' => null,
			'next' => null
		);
	
		$melisPage = $this->getServiceManager()->get('MelisEnginePage');
		$datasPagePublished = $melisPage->getDatasPage($idPage, 'published');
		$datasPagePublishedTree = $datasPagePublished->getMelisPageTree();
	
		$melisTree = $this->getServiceManager()->get('MelisEngineTree');
		$sisters = $melisTree->getPageChildren($datasPagePublishedTree->tree_father_page_id, $publishedOnly);
		$sisters = $sisters->toArray();
	
		if(!empty($sisters)) {
	
			// Get column list for sort
			foreach ($sisters as $key => $row) {
				$order[$key]  = $row['tree_page_order'];
			}
	
			// Sort sisters pages by order field
			array_multisort($order, SORT_ASC, $sisters);
	
			$posInArray = false;
	
			foreach($sisters as $key => $uneSister) {
				if($uneSister['tree_page_id'] == $datasPagePublishedTree->tree_page_id)
					$posInArray = $key;
			}
	
			// If page found, get prev/next
			if($posInArray !== false) {
	
				$posPrevPage = (($posInArray-1) >= 0) ? ($posInArray-1) : null;
				$posNextPage = (($posInArray+1) && array_key_exists($posInArray+1, $sisters)) ? ($posInArray+1) : null;
	
				if(!is_null($posPrevPage)) {
	
					$prevItem = $sisters[$posPrevPage];
					$prevLink = $melisTree->getPageLink($sisters[$posPrevPage]['tree_page_id']);
	
					// Check if page have a name and link
					if(!empty($prevItem['page_name']) && !empty($prevLink)) {
						$output['prev'] = $prevItem;
						$output['prev']['link'] = $prevLink;
					}
				}
	
				if(!is_null($posNextPage)) {
	
					$nextItem = $sisters[$posNextPage];
					$nextLink = $melisTree->getPageLink($sisters[$posNextPage]['tree_page_id']);
	
					// Check if page have a name and link
					if(!empty($nextItem['page_name']) && !empty($nextLink)) {
						$output['next'] = $nextItem;
						$output['next']['link'] = $nextLink;
					}
				}
			}
		}
	
		return $output;
	}

	/**
		* Gets full datas for a page from tree, page saved, paged published, lang
		*/
	public function getFullDatasPage($id, $type = '')
	{
		// Retrieve cache version if front mode to avoid multiple calls
		$cacheKey = 'getFullDatasPage' . $id . '_' . $type;
		$cacheConfig = 'engine_memory_cache';
		$melisEngineCacheSystem = $this->getServiceManager()->get('MelisEngineCacheSystem');
		$results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);

		if (!is_null($results)) return $results;

		$tablePageTree = $this->getServiceManager()->get('MelisEngineTablePageTree');
		$melisPageTreePublished = $tablePageTree->getFullDatasPage($id, $type);

		// Save cache key
		$melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $melisPageTreePublished);
		
		return $melisPageTreePublished;
	}
}
