<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

class MelisPageStyleTable extends MelisGenericTable
{
	public function __construct(TableGateway $tableGateway)
	{
		parent::__construct($tableGateway);
		$this->idField = 'pstyle_id';
	}
	
	public function savePageStyle($pageStyle, $pageId = null)
	{
	    if (!is_null($pageId))
	    {
	        $this->tableGateway->update($pageStyle, array('pstyle_page_id' => $pageId));
	    }
	    else
	    {
	        $this->tableGateway->insert($pageStyle);
	        $pageId = $this->tableGateway->lastInsertValue;
	    }
	    
	    return $pageId;
	}
}
