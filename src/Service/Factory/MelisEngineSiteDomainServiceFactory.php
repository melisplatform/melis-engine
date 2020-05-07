<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Service\Factory;

use MelisEngine\Service\MelisEngineSiteDomainService;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

class MelisEngineSiteDomainServiceFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $sl)
	{ 
		$melisEngineSiteDomainService = new MelisEngineSiteDomainService();
        $melisEngineSiteDomainService->setServiceLocator($sl);
		return $melisEngineSiteDomainService;
	}

}