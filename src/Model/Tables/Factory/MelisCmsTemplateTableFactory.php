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

use MelisEngine\Model\MelisTemplate;
use MelisEngine\Model\Tables\MelisTemplateTable;

class MelisCmsTemplateTableFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $sl)
	{
    	$hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisTemplate());
    	$tableGateway = new TableGateway('melis_cms_template', $sl->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
		
    	return new MelisTemplateTable($tableGateway);
	}

}