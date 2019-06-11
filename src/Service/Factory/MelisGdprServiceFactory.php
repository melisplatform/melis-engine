<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2019 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Service\Factory;


use MelisEngine\Service\MelisGdprService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MelisGdprServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sl)
    {
        $melisGdprService = new MelisGdprService();
        $melisGdprService->setServiceLocator($sl);
        return $melisGdprService;
    }
}