<?php
	
/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Service;
	

interface MelisTreeServiceInterface 
{
	public function getPageChildren($idPage, $publishedOnly = 0);
	
	public function getPageFather($idPage);
	
	public function getPageBreadcrumb($idPage, $typeLinkOnly = 1);
	
	public function getPageLink($idPage, $absolute = false);
	
	public function getDomainByPageId($idPage);
}