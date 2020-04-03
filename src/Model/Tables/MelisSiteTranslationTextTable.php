<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2018 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables;


use MelisCore\Model\Tables\MelisGenericTable;
use Laminas\Db\TableGateway\TableGateway;

class MelisSiteTranslationTextTable extends MelisGenericTable
{
    /**
     * Model table
     */
    const TABLE = 'melis_site_translation_text';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'mstt_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }
}