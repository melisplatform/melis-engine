<?php

namespace MelisEngine\Model\Tables;

/**
 * 
 * Class MelisCmsSiteRobotTable
 * @package MelisCmsSiteRobotTable\Model
 */
use MelisEngine\Model\Tables\MelisGenericTable;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Db\Sql\Expression;

class MelisCmsSiteRobotTable extends MelisGenericTable
{
    protected $tableGateway;
    protected $idField;

    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'robot_id';
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
    public function getData($search = '', $searchableColumns = [], $orderBy = '', $orderDirection = 'ASC', $start = 0, $limit = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('*'));

        if(!empty($searchableColumns) && !empty($search)) {
            foreach($searchableColumns as $column) {
                $select->where->or->like($column, '%'.$search.'%');
            }
        }


        if(!empty($orderBy)) {
            $select->order($orderBy . ' ' . $orderDirection);
        }

        $getCount = $this->tableGateway->selectWith($select);
        // set current data count for pagination
        $this->setCurrentDataCount((int) $getCount->count());

        if(!empty($limit)) {
            $select->limit($limit);
        }

        if(!empty($start)) {
            $select->offset($start);
        }

        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }
}