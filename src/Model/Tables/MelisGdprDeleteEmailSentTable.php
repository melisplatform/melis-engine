<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2019 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables;


use Laminas\Db\TableGateway\TableGateway;

class MelisGdprDeleteEmailSentTable extends MelisGenericTable
{
    /**
     * Model table
     */
    const TABLE = 'melis_core_gdpr_delete_emails_sent';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'mgdprs_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }
}
