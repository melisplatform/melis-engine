<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2018 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables\Factory;


use MelisEngine\Model\MelisSiteTranslation;
use MelisEngine\Model\Tables\MelisSiteTranslationTextTable;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Hydrator\ObjectProperty;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MelisSiteTranslationTextTableFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $sl)
    {
        $hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisSiteTranslation());
        $tableGateway = new TableGateway('melis_site_translation_text', $sl->get('Zend\Db\Adapter\Adapter'), null, $hydratingResultSet);

        return new MelisSiteTranslationTextTable($tableGateway);
    }
}