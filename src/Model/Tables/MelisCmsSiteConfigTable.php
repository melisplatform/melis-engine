<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2019 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables;

use Laminas\Db\TableGateway\TableGateway;

class MelisCmsSiteConfigTable extends MelisGenericTable
{
    /**
     * Model table
     */
    const TABLE = 'melis_cms_site_config';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'sconf_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
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
