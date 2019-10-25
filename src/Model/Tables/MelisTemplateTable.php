<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables;

use MelisCore\Model\Tables\MelisGenericToolTable;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Where;
use Zend\Db\TableGateway\TableGateway;

class MelisTemplateTable extends MelisGenericTable
{
    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'tpl_id';
        $this->cacheResults = true;
    }

    /**
     * Retrieves the data from the Template table in alphabetical order
     * @return NULL|\Zend\Db\ResultSet\ResultSetInterface
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

        $getCount = $this->tableGateway->selectWith($select);
        // set current data count for pagination
        $this->setCurrentDataCount((int)$getCount->count());

        if (!empty($limit)) {
            $select->limit((int)$limit);
        }

        if (!empty($start)) {
            $select->offset((int)$start);
        }

        $resultSet = $this->tableGateway->selectWith($select);

        return $resultSet;
    }
}
