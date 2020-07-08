<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2019 Melis Technology (http://www.melistechnology.com)
 *
 */
namespace MelisEngine\Model\Tables\Factory;

use MelisCore\Model\MelisGdprDeleteEmailsSent;
use MelisCore\Model\Tables\MelisGdprDeleteEmailsSentTable;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Hydrator\ObjectProperty;

class MelisGdprDeleteEmailsSentTableFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sl)
    {
        $hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisGdprDeleteEmailsSent());
        $tableGateway = new TableGateway('melis_core_gdpr_delete_emails_sent', $sl->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);
        
        return new MelisGdprDeleteEmailsSentTable($tableGateway);
    }
}