<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

class MelisPageSavedTable extends MelisGenericTable
{
	public function __construct(TableGateway $tableGateway)
	{
		parent::__construct($tableGateway);
		$this->idField = 'page_id';
	}
	
	public function getSavedSitePagesById($pageId)
	{
	    $select = $this->tableGateway->getSql()->select();
	    
	    $select->where(array('page_type' => array('SITE', 'PAGE')));
	    $select->where('page_id ='.$pageId);
	    $resultSet = $this->tableGateway->selectWith($select);
	    
	    return $resultSet;
	}
}