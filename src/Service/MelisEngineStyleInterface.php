<?php
	
/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Service;

interface MelisEngineStyleInterface 
{
    /**
     * This method retrieves all the styles
     * 
     * @param int $pageId filter result by page Id
     */
    public function getStyles();
    
    /**
     * This method creates/updates a style
     * 
     * @param array $styleData array of data to be created/updated
     * @param int $styleId If null, creates new style else updates
     */
    public function saveStyle($styleData , $styleId = null);
}