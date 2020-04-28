<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2019 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

class MelisCmsCategoryTable extends MelisGenericTable
{
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'mtplc_id';
    }

    public function getCategoryBySite($site_id) {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['*']);
        $select->join(
            'melis_cms_mini_tpl_site_category',
            'mtplc_id = mtplsc_category_id',
            array('*'),
            $select::JOIN_INNER
        );
        $select->join(
            'melis_cms_category_trans',
            'melis_cms_category.mtplc_id = melis_cms_category_trans.mtplc_id',
            array('*'),
            $select::JOIN_INNER
        );
        $select->where->equalTo('mtplsc_site_id', $site_id);
        $select->order('melis_cms_mini_tpl_site_category.mtplc_order ASC');
        return $this->tableGateway->selectWith($select);
    }
}
