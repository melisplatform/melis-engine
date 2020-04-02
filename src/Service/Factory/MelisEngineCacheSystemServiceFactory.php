<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2017 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Service\Factory;

use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\ServiceManager\FactoryInterface;
use MelisEngine\Service\MelisEngineCacheSystemService;

class MelisEngineCacheSystemServiceFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $sl)
	{ 
        $melisEngineCacheSystemService = new MelisEngineCacheSystemService();
		$melisEngineCacheSystemService->setServiceLocator($sl);
		return $melisEngineCacheSystemService;
	}

}