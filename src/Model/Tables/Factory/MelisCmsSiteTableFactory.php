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

use MelisEngine\Model\MelisSite;
use MelisEngine\Model\Tables\MelisSiteTable;

class MelisCmsSiteTableFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $sl)
	{
    	$hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisSite());
    	$tableGateway = new TableGateway('melis_cms_site', $sl->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
		
    	return new MelisSiteTable($tableGateway);
	}

}