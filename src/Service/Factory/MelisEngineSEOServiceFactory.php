<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Service\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;
use MelisEngine\Service\MelisEngineSEOService;

class MelisEngineSEOServiceFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $sl)
	{ 
		$melisEngineSEOService = new MelisEngineSEOService();
		$melisEngineSEOService->setServiceLocator($sl);
		return $melisEngineSEOService;
	}
}