<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2019 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables;

class MelisCmsSiteVarietiesTransTable extends MelisGenericTable
{
    /**
     * Model table
     */
    const TABLE = 'melis_cms_site_varieties_trans';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'mcsvt_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }
}
