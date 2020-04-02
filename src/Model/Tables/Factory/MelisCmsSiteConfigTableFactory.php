<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2019 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables\Factory;

use MelisEngine\Model\MelisCmsSiteConfig;
use MelisEngine\Model\Tables\MelisCmsSiteConfigTable;
use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Stdlib\Hydrator\ObjectProperty;


class MelisCmsSiteConfigTableFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sl)
    {
        $hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisCmsSiteConfig());
        $tableGateway = new TableGateway('melis_cms_site_config', $sl->get('Laminas\Db\Adapter\Adapter'), null, $hydratingResultSet);

        return new MelisCmsSiteConfigTable($tableGateway);
    }
}