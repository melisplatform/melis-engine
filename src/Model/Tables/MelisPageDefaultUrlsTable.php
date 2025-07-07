<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables;

use Laminas\Db\TableGateway\TableGateway;

class MelisPageDefaultUrlsTable extends MelisGenericTable
{
    /**
     * Model table
     */
    const TABLE = 'melis_cms_page_default_urls';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'purl_page_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }
}
