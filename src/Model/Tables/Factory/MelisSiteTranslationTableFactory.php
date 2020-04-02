<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2018 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables\Factory;

use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Stdlib\Hydrator\ObjectProperty;

use MelisEngine\Model\MelisSiteTranslation;
use MelisEngine\Model\Tables\MelisSiteTranslationTable;

class MelisSiteTranslationTableFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $sl)
	{
	    $hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisSiteTranslation());
    	$tableGateway = new TableGateway('melis_site_translation', $sl->get('Laminas\Db\Adapter\Adapter'), null, $hydratingResultSet);
		
    	return new MelisSiteTranslationTable($tableGateway);
	}

}