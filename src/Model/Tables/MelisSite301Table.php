<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

class MelisSite301Table extends MelisGenericTable
{
	public function __construct(TableGateway $tableGateway)
	{
		parent::__construct($tableGateway);
		$this->idField = 's301_id';
	}

    public function getData($search = '', $siteId = null,  $searchableColumns = [], $orderBy = '', $orderDirection = 'ASC', $start = 0, $limit = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('*'));
        $select->join('melis_cms_site', 'melis_cms_site.site_id = melis_cms_site_301.s301_site_id',
            array('site_name','site_label'), $select::JOIN_LEFT);

        if(!empty($searchableColumns) && !empty($search)) {
            foreach($searchableColumns as $column) {
                $select->where->or->like($column, '%'.$search.'%');
            }
        }

        if(!empty($siteId) && !is_null($siteId)){
            $select->where->equalTo("s301_site_id", $siteId);
        }

        if(!empty($orderBy)) {
            $select->order($orderBy . ' ' . $orderDirection);
        }

        $getCount = $this->tableGateway->selectWith($select);
        // set current data count for pagination
        $this->setCurrentDataCount((int) $getCount->count());

        if(!empty($limit)) {
            $select->limit( (int) $limit);
        }

        if(!empty($start)) {
            $select->offset( (int) $start);
        }

        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }
}
