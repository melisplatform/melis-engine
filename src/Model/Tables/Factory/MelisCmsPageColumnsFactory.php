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


class MelisCmsPageColumnsFactory implements FactoryInterface
{
	public function createService(ServiceLocatorInterface $sl)
	{
		$metadata = new \Zend\Db\Metadata\Metadata($sl->get('Zend\Db\Adapter\Adapter'));
		$melisPageColumns = $metadata->getColumnNames('melis_cms_page_saved');
		
		return $melisPageColumns;
	}

}