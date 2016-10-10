<?php
	
/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Service;

interface MelisSearchServiceInterface 
{
    public function createIndex($moduleName, $pageId, $_defaultPath = self::FOLDER_PATH);
    
    public function clearIndex($dir);
    
    public function optimizeIndex($lucenePath);
    
    public function search($searchValue, $moduleName, $returnXml = false, $_defaultPath = self::FOLDER_PATH);
}