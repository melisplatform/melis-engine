<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

class MelisPageLangTable extends MelisGenericTable
{
	public function __construct(TableGateway $tableGateway)
	{
		parent::__construct($tableGateway);
		$this->idField = 'plang_id';
	}
	
	public function savePageLang($pageLang, $pageId = null)
	{
	    if (!is_null($pageId))
	    {
	        $this->tableGateway->update($pageLang, array('plang_page_id' => $pageId));
	    }
	    else
	    {
	        $this->tableGateway->insert($pageLang);
	        $pageId = $this->tableGateway->lastInsertValue;
	    }
	    
	    return $pageId;
	}
	
	public function getPageLanguageById($pageId, $langId)
	{
	    $select = $this->tableGateway->getSql()->select();
	    
	    $select->columns(array('*'));
	    $select->where('plang_page_id ='.$pageId);
	    $select->where('plang_lang_id ='.$langId);
	    
	    $resultSet = $this->tableGateway->selectWith($select);
	    return $resultSet;
	}
}
