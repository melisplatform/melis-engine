<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables\Factory;

use Psr\Container\ContainerInterface;
use Laminas\Db\Metadata\Metadata;
use Laminas\Db\Adapter\Adapter;

class MelisCmsPageColumnsFactory
{
	public function __construct(ContainerInterface $container, $requestedName)
	{
		$metadata = new Metadata($container->get(Adapter::class));
		$melisPageColumns = $metadata->getColumnNames('melis_cms_page_saved');
		
		return $melisPageColumns;
	}
}