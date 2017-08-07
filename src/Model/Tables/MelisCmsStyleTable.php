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
}
