<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables;

use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Hydrator\ObjectPropertyHydrator;
use MelisEngine\Model\Hydrator\MelisCmsStyle;

class MelisCmsStyleTable extends MelisGenericTable
{
    /**
     * Model table
     */
    const TABLE = 'melis_cms_style';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'style_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    /**
     * @return HydratingResultSet
     */
    public function hydratingResultSet()
    {
        return $hydratingResultSet = new HydratingResultSet(new ObjectPropertyHydrator(), new MelisCmsStyle());
    }

    public function getStyles($idPage = null, $status = null)
    {
        $select = $this->tableGateway->getSql()->select();

        if (!is_null($status)) {
            $select->where->equalTo('style_status', '1');
        }

        if (!is_null($idPage)) {
            $select->join('melis_cms_page_style', 'pstyle_style_id = style_id', array(), $select::JOIN_LEFT);

            $select->where->equalTo('pstyle_page_id', $idPage);
        }

        $resultSet = $this->tableGateway->selectWith($select);

        return $resultSet;
    }

    /**
     * @param string $search
     * @param array $searchableColumns
     * @param string $orderBy
     * @param string $orderDirection
     * @param int $start
     * @param null $limit
     * @return mixed
     */
    public function getStyleList($search = '', $searchableColumns = [], $orderBy = '', $orderDirection = 'ASC', $start = 0, $limit = 0)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('*'));

        $select->join('melis_cms_site', 'melis_cms_site.site_id = melis_cms_style.style_site_id', array('*'), $select::JOIN_LEFT);

        if (!empty($searchableColumns) && !empty($search)) {
            foreach ($searchableColumns as $column) {
                $select->where->or->like($column, '%' . $search . '%');
            }
        }

        /** Get "unfiltered" data */
        $unfilteredData = $this->tableGateway->selectWith($select);

        if (!empty($orderBy)) {
            $select->order($orderBy . ' ' . $orderDirection);
        }

        if (!is_null($limit)) {
            $select->limit((int) $limit);
        }

        if (!empty($start)) {
            $select->offset((int) $start);
        }

        $resultSet = $this->tableGateway->selectWith($select);
        $resultSet->getObjectPrototype()->setFilteredDataCount($resultSet->count());
        $resultSet->getObjectPrototype()->setUnfilteredDataCount($unfilteredData->count());

        return $resultSet;
    }
}
