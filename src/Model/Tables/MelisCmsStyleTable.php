<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

class MelisCmsStyleTable extends MelisGenericTable
{
	public function __construct(TableGateway $tableGateway)
	{
		parent::__construct($tableGateway);
		$this->idField = 'style_id';
	}
	
	public function getStyles($idPage = null, $status = null)
	{
	    $select = $this->tableGateway->getSql()->select();
	    
	    if(!is_null($status)){
	        $select->where->equalTo('style_status', '1');
	    }
	    
	    if(!is_null($idPage)){
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
    public function getStyleList($search = '', $searchableColumns = [], $orderBy = '', $orderDirection = 'ASC', $start = 0, $limit = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('*'));

        $select->join('melis_cms_site', 'site_id = style_site_id', array(), $select::JOIN_LEFT);

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
