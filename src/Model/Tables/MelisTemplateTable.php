<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use MelisCore\Model\Tables\MelisGenericToolTable;

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
	    $select->order('tpl_name ASC');

	    $resultSet = $this->tableGateway->selectWith($select);

	   
	   return $resultSet;
	}
}
