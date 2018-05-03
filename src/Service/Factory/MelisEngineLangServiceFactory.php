<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Service\Factory;

use MelisEngine\Service\MelisEngineLangService;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

class MelisEngineLangServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sl)
    {
        $melisEngineLangService = new MelisEngineLangService();
        $melisEngineLangService->setServiceLocator($sl);
        return $melisEngineLangService;
    }

}