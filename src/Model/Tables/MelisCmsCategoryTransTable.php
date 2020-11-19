<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2019 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables;

use MelisCore\Model\Tables\MelisGenericTable;
use Laminas\Db\TableGateway\TableGateway;

class MelisCmsCategoryTransTable extends MelisGenericTable
{
    /**
     * Model table
     */
    const TABLE = 'melis_cms_category_trans';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'mtplct_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    public function getTextByLang($cat_id, $lang_id) {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['*']);
        $select->where->equalTo('mtplc_id', $cat_id);
        $select->where->equalTo('mtplct_lang_id', $lang_id);
        return $this->tableGateway->selectWith($select);
    }
}
