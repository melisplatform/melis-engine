<?php

namespace MelisEngine\Model\Tables\Factory;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Stdlib\Hydrator\ObjectProperty;

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
        $tableGateway       = new TableGateway('melis_cms_domain_robots', $sl->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);

        return new MelisCmsSiteRobotTable($tableGateway);
    }
}