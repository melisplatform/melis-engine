<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2019 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables;

class MelisGdprAutoDeleteConfigTable  extends MelisGenericTable
{
    /**
     * Model table
     */
    const TABLE = 'melis_core_gdpr_delete_config';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'mgdprc_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    /**
     * get auto delete config by site id and module
     *
     * @param $siteId
     * @param $module
     */
    public function getAutoDeleteConfig($siteId, $module)
    {

        $select = $this->getTableGateway()->getSql()->select();

        if (! empty($siteId)) {
            $select->where->equalTo('mgdprc_site_id', $siteId);
        }

        if (! empty($module)) {
            $select->where->equalTo('mgdprc_module_name', $module);
        }

        return $this->getTableGateway()->selectWith($select);
    }
}
