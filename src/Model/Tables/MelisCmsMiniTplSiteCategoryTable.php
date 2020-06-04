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


class MelisCmsMiniTplSiteCategoryTable extends MelisGenericTable
{
    /**
     * Model table
     */
    const TABLE = 'melis_cms_mini_tpl_site_category';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'mtplsc_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
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
