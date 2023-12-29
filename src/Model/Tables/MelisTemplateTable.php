<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables;

use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Hydrator\ObjectPropertyHydrator;
use MelisCore\Model\Tables\MelisGenericToolTable;
use Laminas\Db\Sql\Expression;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Where;
use Laminas\Db\TableGateway\TableGateway;
use MelisEngine\Model\Hydrator\MelisTemplate;

class MelisTemplateTable extends MelisGenericTable
{
    /**
     * Model table
     */
    const TABLE = 'melis_cms_template';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'tpl_id';

    private $cacheResults = true;

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
        $this->cacheResults = true;
    }

    /**
     * @return HydratingResultSet
     */
    public function hydratingResultSet()
    {
        return $hydratingResultSet = new HydratingResultSet(new ObjectPropertyHydrator(), new MelisTemplate());
    }

    /**
     * Retrieves the data from the Template table in alphabetical order
     * @return NULL|\Laminas\Db\ResultSet\ResultSetInterface
     */
    public function getSortedTemplates()
    {
        $select = new Select('melis_cms_template');
        $select->order('tpl_zf2_website_folder ASC');
        $select->order('tpl_name ASC');

        $resultSet = $this->tableGateway->selectWith($select);


        return $resultSet;
    }

    public function getUniqueWebsiteFolder()
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('tpl_zf2_website_folder'));

        $select->group('tpl_zf2_website_folder');

        $select->order('tpl_zf2_website_folder ASC');

        $resultSet = $this->tableGateway->selectWith($select);

        return $resultSet;
    }

    public function getData($search = '', $siteId = null, $searchableColumns = [], $orderBy = '', $orderDirection = 'ASC', $start = 0, $limit = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['*']);
        $select->join(
            'melis_cms_site',
            'melis_cms_site.site_id = melis_cms_template.tpl_site_id',
            ['*'],
            $select::JOIN_LEFT
        );

        /** Get "unfiltered" data */
        $unfilteredData = $this->tableGateway->selectWith($select);

        if (!empty($searchableColumns) && !empty($search)) {
            $where = new Where();
            $nest = $where->nest();

            foreach ($searchableColumns as $column) {
                $nest->like($column, '%' . $search . '%')->or;
            }
            $select->where($where);
        }

        if (!empty($siteId) && !is_null($siteId)) {
            $select->where->equalTo("tpl_site_id", $siteId);
        }

        if (!empty($orderBy)) {
            if ($orderBy == 'tpl_type') {
                $select->order(new Expression("CAST($orderBy AS CHAR) $orderDirection"));
            } else {
                $select->order("$orderBy $orderDirection");
            }
        }

        if (!empty($limit)) {
            $select->limit((int)$limit);
        }

        if (!empty($start)) {
            $select->offset((int)$start);
        }

        $resultSet = $this->tableGateway->selectWith($select);
        $resultSet->getObjectPrototype()->setFilteredDataCount($resultSet->count());
        $resultSet->getObjectPrototype()->setUnfilteredDataCount($unfilteredData->count());

        return $resultSet;
    }
}
