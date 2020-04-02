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
use MelisEngine\Service\MelisEngineStyleService;

class MelisEngineStyleServiceFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $sl)
	{ 
		$melisEngineStyle = new MelisEngineStyleService();
		$melisEngineStyle->setServiceLocator($sl);
		return $melisEngineStyle;
	}

}