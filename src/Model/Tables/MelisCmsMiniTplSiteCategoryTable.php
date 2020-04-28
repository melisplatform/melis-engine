<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2019 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

class MelisCmsMiniTplSiteCategoryTable extends MelisGenericTable
{
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'mtplsc_id';
    }

    public function getLastOrderId() {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['*']);
        $select->order('mtplc_order DESC');
        $select->limit(1);
        return $this->tableGateway->selectWith($select);
    }

    public function getAffectedCategories($order) {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['*']);
        $select->where->greaterThanOrEqualTo('mtplc_order', $order);
        return $this->tableGateway->selectWith($select);
    }
}
