<?php
	
namespace MelisEngine\Service;

interface MelisSearchServiceInterface 
{
    public function createIndex($moduleName, $pageId, $_defaultPath = self::FOLDER_PATH);
    
    public function clearIndex($dir);
    
    public function optimizeIndex($lucenePath);
    
    public function search($searchValue, $moduleName, $returnXml = false, $_defaultPath = self::FOLDER_PATH);
}