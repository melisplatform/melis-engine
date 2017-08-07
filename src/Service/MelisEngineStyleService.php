<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Service;

use MelisEngine\Service\MelisEngineGeneralService;

class MelisEngineStyleService extends MelisEngineGeneralService implements MelisEngineStyleInterface
{
    
    public function getStyles($idPage = null, $status = null)
    {
        
        $styleTable = $this->getServiceLocator()->get('MelisEngineTableStyle');
        
        $stylesData = $styleTable->getStyles($idPage, $status)->toArray();
        
        return $stylesData;
    }
    
    public function saveStyle($styleData , $styleId = null)
    {
        
        $styleTable = $this->getServiceLocator()->get('MelisEngineTableStyle');
        
        $result = $styleTable->save($styleData, $styleId);
        
        return $result;
    }
}