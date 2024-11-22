<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2019 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables;

class MelisCmsSiteVarietiesTable extends MelisGenericTable
{
    /**
     * Model table
     */
    const TABLE = 'melis_cms_site_varieties';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'mcsv_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    /**
     * @param null $langId
     * @return mixed
     */
    public function getSiteVarieties($langId = null)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('*'));

        $select->join('melis_cms_site_varieties_trans', 'melis_cms_site_varieties_trans.mcsvt_mcsv_id = melis_cms_site_varieties.mcsv_id', ['*']);

        if(!empty($langId))
            $select->where->equalTo('mcsvt_lang_id', $langId);

        $resultSet = $this->tableGateway->selectWith($select);

        return $resultSet;
    }

    /**
     * @param null $code
     * @param null $langId
     * @return mixed
     */
    public function getSiteVarietiesByCode($code, $langId = null)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('*'));

        $select->join('melis_cms_site_varieties_trans', 'melis_cms_site_varieties_trans.mcsvt_mcsv_id = melis_cms_site_varieties.mcsv_id', ['*']);

        $select->where->equalTo('mcsv_code', $code);

        if(!empty($langId))
            $select->where->equalTo('mcsvt_lang_id', $langId);

        $resultSet = $this->tableGateway->selectWith($select);

        return $resultSet;
    }
}
