<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Stdlib\Hydrator\ObjectProperty;

use MelisEngine\Model\MelisPlatformIds;
use MelisEngine\Model\Tables\MelisPlatformIdsTable;

class MelisCmsPlatformIdsTableFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $sl)
	{
    	$hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisPlatformIds());
    	$tableGateway = new TableGateway('melis_cms_platform_ids', $sl->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
		
    	return new MelisPlatformIdsTable($tableGateway);
	}
}