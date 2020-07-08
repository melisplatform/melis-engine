<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2019 Melis Technology (http://www.melistechnology.com)
 *
 */
namespace MelisEngine\Model\Tables\Factory;

use MelisEngine\Model\MelisGdprAutoDeleteConfig;
use MelisEngine\Model\Tables\MelisGdprAutoDeleteConfigTable;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Hydrator\ObjectProperty;

class MelisGdprAutoDeleteConfigTableFactory  implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sl)
    {
        $hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisGdprAutoDeleteConfig());
        $tableGateway = new TableGateway('melis_core_gdpr_delete_config', $sl->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
        
        return new MelisGdprAutoDeleteConfigTable($tableGateway);
    }
}
