<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2019 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables;


use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Hydrator\ObjectProperty;
use MelisEngine\Model\Hydrator\MelisCmsGdprTexts;

class MelisCmsGdprTextsTable extends MelisGenericTable
{
    /**
     * Model table
     */
    const TABLE = 'melis_cms_gdpr_texts';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'mcgdpr_text_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    /**
     * @return HydratingResultSet
     */
    public function hydratingResultSet()
    {
        return $hydratingResultSet = new HydratingResultSet(new ObjectProperty(), new MelisCmsGdprTexts());
    }

    public function getGdprBannerText($siteId, $langId)
    {
        $select = $this->tableGateway->getSql()->select();

        if (!empty($siteId)) {
            $select->where->equalTo('mcgdpr_text_site_id', $siteId);
        }

        if (!empty($langId)) {
            $select->where->equalTo('mcgdpr_text_lang_id', $langId);
        }

        $select->where($select);

        return $this->tableGateway->selectWith($select);
    }

    /**
     * Deletes entry via where condition using multiple fields
     *
     * @param array $where
     * @return bool
     */
    public function deleteWhere(array $where = [])
    {
        if (empty($where)) {
            return false;
        } else {
            return $this->tableGateway->delete($where);
        }
    }
}
