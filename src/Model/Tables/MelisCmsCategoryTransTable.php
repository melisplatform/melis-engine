<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2019 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

class MelisCmsCategoryTransTable extends MelisGenericTable
{
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'mtplct_id';
    }

    public function getTextByLang($cat_id, $lang_id) {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['*']);
        $select->where->equalTo('mtplc_id', $cat_id);
        $select->where->equalTo('mtplct_lang_id', $lang_id);
        return $this->tableGateway->selectWith($select);
    }
}
