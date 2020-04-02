<?php

namespace MelisEngine\Model\Tables\Factory;

use Laminas\ServiceManager\ServiceLocatorInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Stdlib\Hydrator\ObjectProperty;

use MelisEngine\Model\MelisCmsSiteRobot;
use MelisEngine\Model\Tables\MelisCmsSiteRobotTable;

class MelisCmsSiteRobotTableFactory implements FactoryInterface
{
    /**
     * MelisCmsSiteRobot/src/MelisCmsSiteRobotFactory/Model/Tables/Factory/MelisCmsSiteRobotTableFactory.php
     * @param ServiceLocatorInterface $sl
     * @return MelisCmsSiteRobot
     */
    public function createService(ServiceLocatorInterface $sl)
    {
        $hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisCmsSiteRobot());
        $tableGateway       = new TableGateway('melis_cms_domain_robots', $sl->get('Laminas\Db\Adapter\Adapter'), null, $hydratingResultSet);

        return new MelisCmsSiteRobotTable($tableGateway);
    }
}