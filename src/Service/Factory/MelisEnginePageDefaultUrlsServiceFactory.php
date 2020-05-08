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
use MelisEngine\Service\MelisEnginePageDefaultUrlsService;

class MelisEnginePageDefaultUrlsServiceFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $sl)
	{ 
		$melisEnginePageDefaultUrlsService = new MelisEnginePageDefaultUrlsService();
		$melisEnginePageDefaultUrlsService->setServiceLocator($sl);
		return $melisEnginePageDefaultUrlsService;
	}
}