<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables\Factory;

use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Stdlib\Hydrator\ObjectProperty;


class MelisCmsPageColumnsFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $sl)
	{
		$metadata = new \Laminas\Db\Metadata\Metadata($sl->get('Laminas\Db\Adapter\Adapter'));
		$melisPageColumns = $metadata->getColumnNames('melis_cms_page_saved');
		
		return $melisPageColumns;
	}

}