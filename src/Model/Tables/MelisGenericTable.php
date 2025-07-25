<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2015 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables;

use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Db\Sql\Select;
use Laminas\Db\Metadata\Metadata;
use Laminas\Db\Sql\Where;
use Laminas\Db\Sql\Predicate\PredicateSet;
use Laminas\Db\Sql\Predicate\Like;
use Laminas\Db\Sql\Predicate\Operator;
use Laminas\Db\Sql\Predicate\Predicate;
use Laminas\Hydrator\ObjectPropertyHydrator;
use Laminas\ServiceManager\ServiceManager;
use MelisEngine\Model\Hydrator\MelisResultSet;

class MelisGenericTable
{
    protected $serviceManager;
    protected $tableGateway;
    protected $idField;
    protected $lastInsertId;
    protected $_selectedColumns;
    protected $_selectedValues;
    protected $_currentDataCount;

    /**
     * @param ServiceManager $serviceManager
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    /**
     * @return mixed
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * @param TableGateway $tableGateway
     */
    public function setTableGateway(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     * @return TableGateway $tableGateway
     */
    public function getTableGateway()
    {
        return $this->tableGateway;
    }

    /**
     * @return HydratingResultSet
     */
    public function hydratingResultSet()
    {
        return $hydratingResultSet = new HydratingResultSet(new ObjectPropertyHydrator(), new MelisResultSet());
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();

        return $resultSet;
    }

    public function getEntryById($id)
    {
        $rowset = $this->tableGateway->select(array($this->idField => (int)$id));
        return $rowset;
    }

    public function getEntryByField($field, $value)
    {
        $rowset = $this->tableGateway->select(array($field => $value));
        return $rowset;
    }

    public function deleteById($id)
    {
        return $this->tableGateway->delete(array($this->idField => (int)$id));
    }

    public function deleteByField($field, $value)
    {
        return $this->tableGateway->delete(array($field => $value));
    }

    public function save($datas, $id = null)
    {
        $id    = (int) $id;

        if ($this->getEntryById($id)->current()) {
            $this->tableGateway->update($datas, array($this->idField => $id));
            return $id;
        } else {
            $this->tableGateway->insert($datas);
            $insertedId = $this->tableGateway->lastInsertValue;
            return $insertedId;
        }
    }

    public function update($datas, $whereField, $whereValue)
    {
        if ($this->getEntryByField($whereField, $whereValue)->current()) {
            $this->tableGateway->update($datas, array($whereField => $whereValue));
        }
    }

    public function getLastInsertId()
    {
        return $this->lastInsertId;
    }

    protected function aliasColumnsFromTableDefinition($serviceTableName, $prefix)
    {
        $melisPageColumns = $this->getServiceManager()->get($serviceTableName);

        $final = array();
        foreach ($melisPageColumns as $column)
            $final[$prefix . $column] = $column;

        return $final;
    }


    /**
     * Returns the columns of the table
     * @param Array $table
     */
    public function getTableColumns()
    {
        $metadata = new MetaData($this->tableGateway->getAdapter());
        $columns = $metadata->getColumnNames($this->tableGateway->getTable());

        return $columns;
    }

    /**
     * Returns the data from a specific fields<br/>
     * Sample Usage: fetchTableData(array('name, 'age'));<br/>
     * If no parameter is supplied, this will automatically map<br/>
     * to the table's columns.<br/>
     *
     * @param Array $columns | optional
     * @return Array $data
     */
    public function fetchData($columns = null)
    {

        // auto populate columns with arrays from the existing Table
        // when user does not supply a parameter to this function
        if ($columns == null) {
            $this->_selectedColumns = $this->getTableColumns();
        } else {
            if (is_array($columns)) {
                $this->_selectedColumns = $columns;
            } else {
                throw new \InvalidArgumentException('Invalid argument provided on Column parameter');
            }
        }

        $select = new Select();

        $select->columns(array_keys($this->_selectedColumns));
        $select->from($this->tableGateway->getTable());
        $resultSet = $this->tableGateway->selectWith($select);
        $resultSet->buffer();
        $this->_selectedValues = $resultSet;

        return $resultSet;
    }

    /**
     * Returns the currently selected columns from a query
     * @return Array
     */
    public function getSelectedColumns()
    {
        if (!empty($this->_selectedColumns)) {
            return $this->_selectedColumns;
        } else {
            return $this->getTableColumns();
        }
    }

    /**
     * This is used whenever you want to implement a pagination on your data table
     * @tutorial Array Structure
     * array(
     *           'where' => array(
     *               'key' => 'pros_id',
     *               'value' => $search,
     *           ),
     *           'order' => array(
     *               'key' => $selCol,
     *               'dir' => $sortOrder,
     *           ),
     *           'start' => $start,
     *           'limit' => $length,
     *           'columns' => $colId
     *       )
     * @param array $options
     * @param array $fixedCriteria (optional)
     * @return \Laminas\Db\ResultSet\ResultSetInterface
     */
    public function getPagedData(array $options, $fixedCriteria = null)
    {

        $select = $this->tableGateway->getSql()->select();
        $result = $this->tableGateway->select();

        $where = !empty($options['where']['key']) ? $options['where']['key'] : '';
        $whereValue = !empty($options['where']['value']) ? $options['where']['value'] : '';

        $order = !empty($options['order']['key']) ? $options['order']['key'] : '';
        $orderDir = !empty($options['order']['dir']) ? $options['order']['dir'] : 'ASC';

        $start = (int) $options['start'];
        $limit = (int) $options['limit'] === -1 ? $this->getTotalData() : (int) $options['limit'];

        $columns = $options['columns'];

        //$select->join('melis_cms_site', 'melis_cms_site.site_id = melis_cms_style.style_site_id', $select::SQL_STAR, $select::JOIN_LEFT);

        // check if there's an extra variable that should be included in the query
        $dateFilter = $options['date_filter'];
        $dateFilterSql = '';

        if (count($dateFilter)) {
            if (!empty($dateFilter['startDate']) && !empty($dateFilter['endDate'])) {
                $dateFilterSql = '`' . $dateFilter['key'] . '` BETWEEN \'' . $dateFilter['startDate'] . '\' AND \'' . $dateFilter['endDate'] . '\'';
            }
        }

        // this is used when searching
        if (!empty($where)) {
            $w = new Where();
            $p = new PredicateSet();
            $filters = array();
            $likes = array();
            foreach ($columns as $colKeys) {
                $likes[] = new Like($colKeys, '%' . $whereValue . '%');
            }

            if (!empty($dateFilterSql)) {
                $filters = array(new PredicateSet($likes, PredicateSet::COMBINED_BY_OR), new \Laminas\Db\Sql\Predicate\Expression($dateFilterSql));
            } else {
                $filters = array(new PredicateSet($likes, PredicateSet::COMBINED_BY_OR));
            }
            $fixedWhere = array(new PredicateSet(array(new Operator('', '=', ''))));
            if (is_null($fixedCriteria)) {
                $select->where($filters);
            } else {
                $select->where(array(
                    $fixedWhere,
                    $filters,
                ), PredicateSet::OP_AND);
            }
        }


        // used when column ordering is clicked
        if (!empty($order))
            $select->order($order . ' ' . $orderDir);

        $getCount = $this->tableGateway->selectWith($select);
        $this->setCurrentDataCount((int) $getCount->count());

        // this is used in paginations
        $select->limit($limit);
        $select->offset($start);

        $resultSet = $this->tableGateway->selectWith($select);

        $sql = $this->tableGateway->getSql();
        $raw = $sql->getSqlstringForSqlObject($select);

        return $resultSet;
    }


    /**
     * Returns the total rows of the selected table
     * @return int
     */
    public function getTotalData($field = null, $idValue = null)
    {
        $select = $this->tableGateway->getSql()->select();

        if (!empty($field) && !empty($idValue)) {
            $select->where(array($field => (int) $idValue));
        }

        $resultSet = $this->tableGateway->selectWith($select);

        return $resultSet->count();
    }

    /**
     * Returns the total count of the filtered data
     * @return int
     */
    public function getTotalFiltered()
    {
        return $this->_currentDataCount;
    }

    /**
     * Returns the data that matches the filters for the
     * selected column(s), & site (only when provided)
     *
     * Site format:
     *  [
     *     'columnName' => (string) table's column name for the site,
     *     'id' => (int|string) site's id
     *  ]
     *
     * @param $filter
     * @param array $columns
     * @param array $site
     * @return \Laminas\Db\ResultSet\ResultSetInterface
     */
    public function getDataForExport($filter, $columns = [], array $site = [])
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['*']);

        if (!empty($filter) && is_string($filter)) {
            $likes = [];
            foreach ($columns as $columnKeys) {
                $likes[] = new Like($columnKeys, '%' . $filter . '%');
            }

            $filters = [new PredicateSet($likes, PredicateSet::COMBINED_BY_OR)];
            $select->where($filters);
        }

        if (!empty($site['id']) && !empty($site['columnName'])) {
            $select->where->equalTo($site['columnName'], $site['id']);
        }

        $resultSet = $this->tableGateway->selectWith($select);

        return $resultSet;
    }

    /**
     * @deprecated Do not use
     * Returns the corresponding values in a column
     */
    protected function getSelectedValues()
    {
        $resultSet = array();
        foreach ($this->_selectedValues as $keys => $values) {
            // cast object into array
            $resultSet[] = (array)$values;
        }
        return $resultSet;
    }

    /**
     * @param $field
     * @param $value
     * @return mixed
     */
    public function getEntryByFieldUsingLike($field, $value)
    {
        $select = $this->tableGateway->getSql()->select();
        $where = new Where();
        $where->like($field, '%' . $value . '%');
        $select->where($where);
        $rowset = $this->tableGateway->selectwith($select);

        return $rowset;
    }

    protected function setCurrentDataCount($dataCount)
    {
        $this->_currentDataCount = $dataCount;
    }

    protected function getRawSql($select)
    {
        $sql = $this->tableGateway->getSql();
        $raw = $sql->getSqlstringForSqlObject($select);

        return $raw;
    }

    public function getByFields($fields)
    {
        $select = $this->getTableGateway()->getSql()->select();

        if (is_array($fields))
            $select->where($fields);
        else
            $select->limit(0);

        return $this->getTableGateway()->selectWith($select);
    }

    public function getAdapter()
    {
        return $this->getTableGateway()->getAdapter();
    }

    public function getSelect()
    {
        return $this->getTableGateway()->getSql()->select();
    }

    public function select($select)
    {
        return $this->getTableGateway()->selectWith($select);
    }
}
