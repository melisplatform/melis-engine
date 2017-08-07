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

use MelisEngine\Model\MelisCmsStyle;
use MelisEngine\Model\Tables\MelisCmsStyleTable;

class MelisCmsStyleTableFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $sl)
	{
    	$hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisCmsStyle());
    	$tableGateway = new TableGateway('melis_cms_style', $sl->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
		
    	return new MelisCmsStyleTable($tableGateway);
	}

}