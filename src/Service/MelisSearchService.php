<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZendSearch\Lucene\Lucene;
use ZendSearch\Lucene\Document;

/**
 * Search service for melis search engine based on ZendSearch
 *
 */
class MelisSearchService implements ServiceLocatorAwareInterface
{
    public $serviceLocator;
    protected $tmpLogs;
    protected $unreachableCount = 0;
    /**
     * Output encoding
     */
    const ENCODING = 'UTF-8';

    /**
     * Path where the lucene index should be created
     */
    const FOLDER_PATH = 'module/MelisSites/';

    /**
     * Folder name of the lucene index
     */
    const FOLDER_NAME = 'luceneIndex';

    /**
     * HTTP max timeout| DEFAULT IS 60 minutes
     */
    const MAX_TIMEOUT_MINS = 60;

    /**
     * HTTP GOOD RESPONSE
     */
    const HTTP_NOT_OK = 404;

    /**
     * ACTIVE PAGE FLAG
     */
    const PAGE_ACTIVE = 1;

    public function setServiceLocator(ServiceLocatorInterface $sl)
    {
        $this->serviceLocator = $sl;
        return $this;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }


    /**
     * Create index for the provided page id
     *
     * @param string $moduleName
     * @param int $pageId
     * @param string[] $exclude
     * @param string|optional $_defaultPath
     */
    public function createIndex($moduleName, $pageId, $exclude = array(),
                                $_defaultPath = self::FOLDER_PATH)
    {
        $this->tmpLogs = '';

        $pageContent = '';
        $folderPath = $_defaultPath . $moduleName;
        $lucenePath = $folderPath. '/' .self::FOLDER_NAME;

        // check if the module exists
        if(file_exists($folderPath)) {
            // check if the path exists
            if(!file_exists($lucenePath)) {

                $this->createDir($lucenePath);

                $this->tmpLogs = $this->createIndexForPage($lucenePath, $pageId, $exclude);
            }
            else {
                $this->changePermission($lucenePath);

                $this->tmpLogs = $this->createIndexForPage($lucenePath, $pageId, $exclude);

            }
        }


        return $this->tmpLogs;
    }


    /**
     * Used to clear index folder
     *
     * @param string $dir
     *
     * @return number
     */
    public function clearIndex($dir)
    {
        $success = 0;

        if (!file_exists($dir))
        {
            $success = 1;
        }

        if (!is_dir($dir))
        {
            return @unlink($dir);
        }

        foreach (scandir($dir) as $item)
        {
            if ($item == '.' || $item == '..')
            {
                continue;
            }

            if (!$this->clearIndex($dir . DIRECTORY_SEPARATOR . $item))
            {
                $success = 0;
            }

        }

        $sucess = @rmdir($dir);

        return $sucess;
    }


    /**
     * Use this function to optimize your lucene indexes
     *
     * @param string $moduleName
     */
    public function optimizeIndex($moduleName)
    {
        $translator = $this->getServiceLocator()->get('translator');
        $status = $translator->translate('tr_melis_engine_search_optimize');
        $lucenePath = self::FOLDER_PATH.$moduleName.'/'.self::FOLDER_NAME . '/indexes';

        if(file_exists($lucenePath) && is_readable($lucenePath)) {
            $index = Lucene::open($lucenePath);
            $index->optimize();
        }

        return $status;

    }

    /**
     * Make a search
     *
     * @param string $moduleName
     * @param string $searchValue
     * @param string $returnXml
     * @param string|optional $_defaultPath
     */
    public function search($searchValue, $moduleName, $returnXml = false,
                           $_defaultPath = self::FOLDER_PATH)
    {
        $results = array();

        $lucenePath = self::FOLDER_PATH.$moduleName.'/'.self::FOLDER_NAME . '/indexes';

        if(file_exists($lucenePath) && is_readable($lucenePath)) {

            $index = Lucene::open($lucenePath);
            $results = $index->find($searchValue);

            if($returnXml)
                $results = $this->setSearchResultsAsXml($searchValue, $results);
        }

        return $results;
    }


    /**
     * Returns the search results as XML
     *
     * @param string $searchValue
     * @param array $searchResults
     * @return string
     */
    protected function setSearchResultsAsXml($searchValue, $searchResults)
    {
        $pagePublishTable = $this->getServiceLocator()->get('MelisEngineTablePagePublished');

        $xmlContent = '<?xml version="1.0" encoding="UTF-8"?>';
        $xmlContent.= '<document type="MelisSearchResults" author="MelisTechnology" version="2.0">';
        $xmlContent.= '<searchQuery>'.$searchValue.'</searchQuery>';

        $lastEditedDate = null;
        $pageStatus     = null;
        $totalResults   = 0;

        foreach($searchResults as $result) {

            $pageData = $pagePublishTable->getEntryById($result->page_id)->current();

            if($pageData) {
                $lastEditedDate = $pageData->page_edit_date;
                $pageStatus     = $pageData->page_status;
            }

            $description = $this->limitedText($result->description);

            $xmlContent .= '<result>';
            $xmlContent .= '    <score>' . round($result->score, 2, PHP_ROUND_HALF_EVEN) . '</score>';
            $xmlContent .= '    <pageStatus>' . $pageStatus         .'</pageStatus>';
            $xmlContent .= '    <url>'        . $result->url        .'</url>';
            $xmlContent .= '    <pageId>'     . $result->page_id    .'</pageId>';
            $xmlContent .= '    <pageName>'   . $result->page_name  .'</pageName>';
            $xmlContent .= '    <lastPageEdit>' . $lastEditedDate   .'</lastPageEdit>';
            $xmlContent .= '    <description>' .  $description  .'</description>';
            $xmlContent .= '</result>';

            $totalResults++;
        }
        $xmlContent.= '<totalResults>'.$totalResults.'</totalResults>';


        $xmlContent.= '</document>';

        return $xmlContent;
    }
    public function createIndexRec($pageId, $index, $exclude)
    {
        $pages = new \MelisFront\Navigation\MelisFrontNavigation($this->getServiceLocator(), $pageId, 'front');
        $pages = $pages->getChildrenRecursive($pageId);

        if($pages) {

            foreach($pages as $idx => $page) {

                $pageId = (int) $page['idPage'] ?? null;
                $pageStat = $page['pageStat'];

                if(!in_array($pageId,$exclude)){

                    if($pageId) {
                        if($pageStat){
                            $this->totalCount++;

                            $indexData =  $index->find('page_id:' . $pageId);
                            $tmpData = array();
                            foreach($indexData as $data) {
                                $index->delete($data->id);
                            }

                            // Add Index
                            $index->addDocument($this->createDocument(array(
                                'page_id' => $pageId,
                                'page_name' => $page['label'],
                            )));
                        }

                        if($page['pages']) {
                            $this->createIndexRec($pageId, $index, $exclude);
                        }
                    }
                }

            }
        }

        return $this->totalCount;
    }

    /**
     * Create index with a content of a specific page
     *
     * @param string $lucenePath
     * @param int $pageId
     * @param array $exclude
     */
    protected function createIndexForPage($lucenePath, $pageId, $exclude = array())
    {

        $totalPage = 1;
        $this->unreachableCount = 0;
        $pagePub = $this->getServiceLocator()->get('MelisEngineTablePagePublished');
        $pageSave  = $this->getServiceLocator()->get('MelisEngineTablePageSaved');
        $enginePage = $this->getServiceLocator()->get('MelisEngineTree');
        $translator = $this->getServiceLocator()->get('translator');

        $pageData = $pagePub->getEntryById((int) $pageId)->current();

        //if there is no page in page published get it in the pagesaved
        if(!$pageData){
            $pagedata = $pageSave->getEntryById((int) $pageId)->current();
        }

        if($pageData) {

            // index and tmp index path
            $this->tmpLogs .= 'OK ' . $translator->translate('tr_melis_engine_search_create_temp_folder') . PHP_EOL . '<br/>';
            $this->createDir($lucenePath.'/generated');
            if(file_exists($lucenePath.'/generated')) {
                // make sure to clear it first before adding new indexes
                $this->clearIndex($lucenePath.'/generated');

                $pages = new \MelisFront\Navigation\MelisFrontNavigation($this->getServiceLocator(), $pageId, 'front');
                $pages = $pages->getChildrenRecursive($pageId);

                $index = Lucene::create($lucenePath.'/generated');
                $doc   = new Document();

                //Only for published pages
                if($pageData->page_status){
                    //create index for the provided page id
                    $index->addDocument($this->createDocument(array(
                        'page_id' => $pageData->page_id,
                        'page_name' => $pageData->page_name,
                    )));
                    $totalPage = $this->createIndexRec($pageId, $index,$exclude ) + 1;
                }else{

                    $totalPage = $this->createIndexRec($pageId, $index , $exclude);
                }


                $index->commit();
                $difference = ($totalPage - $this->unreachableCount);
                $percentage = round(($difference / $totalPage) * 100);
                $loss = round(($this->unreachableCount / $totalPage) * 100);

                $this->tmpLogs .= 'OK ' . sprintf($translator->translate('tr_melis_engine_search_total_created'), $difference, $totalPage, $percentage) . PHP_EOL . '<br/>';
                if($loss > 70) {
                    $this->tmpLogs .= 'KO ' . $translator->translate('tr_melis_engine_search_indexing_failed') . PHP_EOL . '<br/>';
                }
                else {

                    // delete the indexes folder
                    if(file_exists($lucenePath.'/indexes')) {
                        $this->clearIndex($lucenePath.'/indexes');
                    }

                    if($this->isDirEmpty($lucenePath.'/indexes')) {
                        $this->tmpLogs .= 'OK ' . $translator->translate('tr_melis_engine_search_delete_old_index_success') . PHP_EOL . '<br/>';

                        $this->forceRename($lucenePath.'/generated', $lucenePath.'/indexes');

                        if(file_exists($lucenePath.'/indexes')) {

                            $this->tmpLogs .= 'OK ' . $translator->translate('tr_melis_engine_search_switch_folder_success') . PHP_EOL . '<br/>';
                            $this->tmpLogs .= 'OK ' . $translator->translate('tr_melis_engine_search_results') . PHP_EOL . '<br/>';

                            //$this->clearIndex($lucenePath.'/generated');
                        }
                        else {
                            $this->tmpLogs .= 'KO ' . $translator->translate('tr_melis_engine_search_delete_old_index_failed') . PHP_EOL . '<br/>';
                        }
                    }
                    else {
                        $this->tmpLogs .= 'KO ' . $translator->translate('tr_melis_engine_search_delete_old_index_failed') . PHP_EOL . '<br/>';
                    }
                }


            } // end file_exists
            else {
                $this->tmpLogs .= 'KO ' . $translator->translate('tr_melis_engine_search_create_temp_folder_fail') . PHP_EOL . '<br/>';
            }

        }
        else {
            $this->tmpLogs .= 'KO ' . sprintf($translator->translate('tr_melis_engine_search_create_index'), $pageId) . $translator->translate('tr_melis_engine_search_create_index_fail_page_not_exists') . PHP_EOL . '<br/>';
        }

        return $this->tmpLogs;
    }

    /**
     * Returns a document class that will be added in the index
     * @param array $data
     * @param string $this->tmpLogs
     * @return Lucene\Document
     */
    protected function createDocument($data = array())
    {
        $enginePage = $this->getServiceLocator()->get('MelisEngineTree');
        $translator = $this->getServiceLocator()->get('translator');

        $doc = new Document();
        if(is_array($data)) {
            $uri = $enginePage->getPageLink($data['page_id'], true);
            $pattern = '/(http|https)\:\/\/(www\.)?[a-zA-Z0-9-_.]+(\.([a-z.])?)*/';
            $domain = $this->getCurrentDomain();
            if(!preg_match($pattern, $uri)) {
                $uri = $domain . $uri;
            }

            $pageContent = $this->getUrlContent($uri);

            if($pageContent) {

                $doc->addField(Document\Field::Text('description', $enginePage->cleanString($this->getHtmlDescription($pageContent))));
                $doc->addField(Document\Field::Keyword('url', $uri));
                $doc->addField(Document\Field::Keyword('page_id', $data['page_id']));
                $doc->addField(Document\Field::Keyword('page_name', $data['page_name']));
                $doc->addField(Document\Field::UnStored('contents', $enginePage->cleanString($pageContent), self::ENCODING));


                $this->tmpLogs .= 'OK ' . sprintf($translator->translate('tr_melis_engine_search_create_index'), $data['page_id']) . sprintf($translator->translate('tr_melis_engine_search_create_index_success'), $uri) . PHP_EOL . '<br/>';
            }
            else {
                $this->tmpLogs .= 'KO ' . sprintf($translator->translate('tr_melis_engine_search_create_index'), $data['page_id']) . $translator->translate('tr_melis_engine_search_create_index_fail_unreachable') . ', ' . $uri . PHP_EOL . '<br/>';
                $this->unreachableCount++;
            }

        }

        return $doc;

    }

    protected function getCurrentDomain()
    {
        $env = getenv('MELIS_PLATFORM');
        $url = '/';
        if($env) {
            $table = $this->getServiceLocator()->get('MelisEngineTableSiteDomain');
            $data  = $table->getEntryByField('sdom_env', $env)->current();
            if(!empty($data)) {
                $url = $data->sdom_scheme . '://' . $data->sdom_domain;
            }
        }

        return $url;
    }

    /**
     * Get's a brief description about the html string provided
     * @param String $html
     * @return String
     */
    protected function getHtmlDescription($html)
    {
        $content = '';
        $doc = new \DOMDocument;
        @$doc->loadHTML($html);
        $xpath = new \DOMXPath($doc);

        $query = '//p[preceding-sibling::p]';

        foreach ($xpath->query($query) as $node) {
            $content .= trim($node->textContent, PHP_EOL);
        }

        return $content;
    }



    /**
     * Retrieves the content of the given url
     * @param String $url
     */
    protected function getUrlContent($url)
    {
        $contents = '';
        $time = (int) self::MAX_TIMEOUT_MINS * 60;

        $timeout = stream_context_create(array(
            'http' => array(
                'timeout' => $time,
            ),
        ));
        set_time_limit($time);
        ini_set('max_execution_time', $time);

        // check if the URL is valid
        if($this->isValidUrl($url)) {
            // make sure we are not getting 404 when accessing the page
            if( (int) $this->getUrlStatus($url) != self::HTTP_NOT_OK ) {
                // ge the contents of the page
                $contents = @file_get_contents($url, false, $timeout);

                // if the contents has results
                if($contents === true) {
                    // convert encodings
                    $contents = mb_convert_encoding($contents, 'HTML-ENTITIES', self::ENCODING);
                }
            }
        }



        return $contents;
    }

    /**
     * Returns the header status of the URL
     * @param string $url
     */
    protected function getUrlStatus($url)
    {
        if($this->isValidUrl($url)) {
            ini_set('allow_url_fopen', 1);
            $url = @get_headers($url, 1);
            if($url) {
                $status = explode(' ',$url[0]);
                return (int) $status[1];
            }
        }
        else {
            return 404;
        }
    }

    /**
     * Checks wether the provided path is empty or not
     * @param string $path
     */
    protected function isDirEmpty($path)
    {
        $files = @glob($path . '/*');

        if(count($files) === 0) {
            return true;
        }

        return false;
    }

    /**
     * Force creation of directory that can be read and written
     * @param string $path
     * @return bool
     */
    protected function createDir($path)
    {
        $status = false;

        if(!file_exists($path)) {

            $oldmask = umask(0);
            mkdir($path, 0755);
            umask($oldmask);

            // check if the directory is readable and writable
            $status = $this->changePermission($path);
        }
        else {
            $status = $this->changePermission($path);
        }

        return $status;

    }

    /**
     * Change folder permission to 0755
     *
     * @param string $path
     */
    protected function changePermission($path, $octal = 0755)
    {
        $status = false;

        if(!is_writable($path))
            chmod($path, $octal);

        if(!is_readable($path))
            chmod($path, $octal);

        if(is_readable($path) && is_writable($path))
            $status = true;

        return $status;

    }

    protected function forceRename($oldPathName, $newPathName)
    {
        $status = true;

        if ($this->recurse_copy($oldPathName, $newPathName)) {
            unlink($oldPathName);
            $status = true;
        }

        return $status;

    }

    protected function recurse_copy($src,$dst)
    {
        $dir = opendir($src);
        @mkdir($dst);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    recurse_copy($src . '/' . $file,$dst . '/' . $file);
                }
                else {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    /**
     * Returns a limited text
     *
     * @param string $text
     * @param int $limit
     * @return string
     */
    protected function limitedText($text, $limit = 200)
    {
        $postString = '...';
        $strCount = strlen(trim($text));
        $sLimitedText = $text;

        if($strCount > $limit)
        {
            $sLimitedText = substr($text, 0, $limit) . $postString;
        }

        return $sLimitedText;

    }

    /**
     * Make sure we have a valid URL when accessing a page
     *
     * @param string $url
     */
    protected function isValidUrl($url)
    {
        $valid = false;

        $parseUrl = parse_url($url);

        if(isset($parseUrl['host']) || !empty($parseUrl['host'])) {

            $uri = new \Zend\Validator\Uri();
            if ($uri->isValid($url)) {
                $valid = true;
            }
            else {
                $valid = false;
            }
        }

        return $valid;
    }


}