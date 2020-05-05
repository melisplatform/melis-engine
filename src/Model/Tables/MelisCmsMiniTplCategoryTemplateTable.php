<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2019 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

class MelisCmsMiniTplCategoryTemplateTable extends MelisGenericTable
{
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'mtplct_id';
    }

    public function getTemplatesByCategoryIds($cat_ids = []) {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['*']);
        $select->where->in('mtplct_category_id', $cat_ids);
        $select->order('mtplct_order ASC');
        return $this->tableGateway->selectWith($select);
    }

    public function getAffectedMiniTemplates($order) {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['*']);
        $select->where->greaterThanOrEqualTo('mtplct_order', $order);
        $select->order('mtplct_order ASC');
        return $this->tableGateway->selectWith($select);
    }

    public function getLatestOrder($cat_id) {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['*']);
        $select->where->equalTo('mtplct_category_id', $cat_id);
        $select->order('mtplct_order DESC');
        $select->limit(1);
        return $this->tableGateway->selectWith($select);
    }
}
