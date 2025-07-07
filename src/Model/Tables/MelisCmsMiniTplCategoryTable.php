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

class MelisCmsMiniTplCategoryTable extends MelisGenericTable
{
    /**
     * Model table
     */
    const TABLE = 'melis_cms_mini_tpl_category';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'mtplc_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    public function getCategoryBySite($site_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['*']);
        $select->join(
            'melis_cms_mini_tpl_site_category',
            'mtplc_id = mtplsc_category_id',
            array('*'),
            $select::JOIN_INNER
        );
        $select->join(
            'melis_cms_mini_tpl_category_trans',
            'melis_cms_mini_tpl_category.mtplc_id = melis_cms_mini_tpl_category_trans.mtplc_id',
            array('*'),
            $select::JOIN_INNER
        );
        // get site label
        $select->join('melis_cms_site', "melis_cms_site.site_id = melis_cms_mini_tpl_site_category.mtplsc_site_id", 'site_label', 'left');

        $select->where->equalTo('mtplsc_site_id', $site_id);
        $select->order('melis_cms_mini_tpl_site_category.mtplc_order ASC');
        return $this->tableGateway->selectWith($select);
    }
}
