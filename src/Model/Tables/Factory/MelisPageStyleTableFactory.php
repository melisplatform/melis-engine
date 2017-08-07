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

use MelisEngine\Model\MelisPageStyle;
use MelisEngine\Model\Tables\MelisPageStyleTable;

class MelisPageStyleTableFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $sl)
	{
    	$hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisPageStyle());
    	$tableGateway = new TableGateway('melis_cms_page_style', $sl->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
		
    	return new MelisPageStyleTable($tableGateway);
	}

}