<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Service\Factory;

use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\ServiceManager\FactoryInterface;
use MelisEngine\Service\MelisEngineGeneralService;

class MelisEngineGeneralServiceFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $sl)
	{ 
        $melisEngineGeneralService = new MelisEngineGeneralService();
		$melisEngineGeneralService->setServiceLocator($sl);
		return $melisEngineGeneralService;
	}

}