<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2019 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Service\Factory;


use MelisEngine\Service\MelisGdprAutoDeleteService;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class MelisGdprAutoDeleteServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sl)
    {
        $melisGdprAutoDeleteService = new MelisGdprAutoDeleteService();
        $melisGdprAutoDeleteService->setServiceLocator($sl);

        return $melisGdprAutoDeleteService;
    }
}