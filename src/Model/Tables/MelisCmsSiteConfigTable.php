<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2019 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

class MelisCmsSiteConfigTable extends MelisGenericTable
{
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'sconf_id';
    }

    public function deleteConfig($configId, $siteId, $langId)
    {
        $delete = $this->tableGateway->getSql()->delete(null);

        if ($configId) {
            $delete->where->equalTo('sconf_id', $configId);
        }

        if ($siteId) {
            $delete->where->equalTo('sconf_site_id', $siteId);
        }

        if ($langId) {
            $delete->where->equalTo('sconf_lang_id', $langId);
        }

        return $this->tableGateway->deleteWith($delete);
    }
}
