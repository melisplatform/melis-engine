<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2018 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables;

use MelisCore\Model\Tables\MelisGenericTable;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Join;

class MelisSiteTranslationTable extends MelisGenericTable
{
    protected $tableGateway;
    protected $idField;

    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'mst_id';
    }

    /**
     * Function to get Site Translation by key
     * language id and site id
     *
     * @param $key
     * @param $langId
     * @param $siteId
     * @return mixed
     */
    public function getSiteTranslation($key, $langId, $siteId)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->join(array('mstt'=>'melis_site_translation_text'), 'mstt.mstt_mst_id = melis_site_translation.mst_id');
        $select->join(array('lang'=>'melis_cms_lang'), 'mstt.mstt_lang_id = lang.lang_cms_id');

        if(!is_null($key) && !empty($key)){
            $select->where->equalTo("melis_site_translation.mst_key", $key);
        }
        if(!empty($langId)) {
            $select->where->equalTo("lang.lang_cms_id", $langId);
        }
        $select->where->equalTo("melis_site_translation.mst_site_id", $siteId);

        $data = $this->tableGateway->selectWith($select);
        return $data;
    }
}