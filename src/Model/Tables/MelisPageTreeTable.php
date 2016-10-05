<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2015 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

class MelisPageTreeTable extends MelisGenericTable
{
	public function __construct(TableGateway $tableGateway)
	{
		parent::__construct($tableGateway);
		$this->idField = 'tree_page_id';
	}
	
	public function getFullDatasPage($id, $type = '')
	{
		$select = $this->tableGateway->getSql()->select();
		
		$select->columns(array('*'));
		
		$select->join('melis_cms_page_lang', 'melis_cms_page_lang.plang_page_id = melis_cms_page_tree.tree_page_id', 
						array('plang_lang_id'));
		$select->join('melis_cms_lang', 'melis_cms_lang.lang_cms_id = melis_cms_page_lang.plang_lang_id', 
						array('*'), $select::JOIN_LEFT);
		
		if ($type == 'published' || $type == '')
			$select->join('melis_cms_page_published', 'melis_cms_page_published.page_id = melis_cms_page_tree.tree_page_id', 
							array('*'), $select::JOIN_LEFT);
		
		if ($type == 'saved')
			$select->join('melis_cms_page_saved', 'melis_cms_page_saved.page_id = melis_cms_page_tree.tree_page_id', 
							array('*'), $select::JOIN_LEFT);
		
		if ($type == '')
		{
			$columns = $this->aliasColumnsFromTableDefinition('MelisEngine\MelisPageColumns', 's_');
			$select->join('melis_cms_page_saved', 'melis_cms_page_saved.page_id = melis_cms_page_tree.tree_page_id',
						$columns, $select::JOIN_LEFT);
		}
		
		$select->where('tree_page_id = ' . $id);
		
		$resultSet = $this->tableGateway->selectWith($select);
		
		return $resultSet;
	}
	
	public function getPageChildrenByidPage($id, $publishedOnly = 0)
	{
		$select = $this->tableGateway->getSql()->select();
		
		$select->columns(array('*'));
		
		$select->join('melis_cms_page_lang', 'melis_cms_page_lang.plang_page_id = melis_cms_page_tree.tree_page_id', 
						array('plang_lang_id'));
		$select->join('melis_cms_lang', 'melis_cms_lang.lang_cms_id = melis_cms_page_lang.plang_lang_id', 
						array('*'), $select::JOIN_LEFT);
		
		$select->join('melis_cms_page_published', 'melis_cms_page_published.page_id = melis_cms_page_tree.tree_page_id', 
						array('*'), $select::JOIN_LEFT);
		if ($publishedOnly == 1)
			$select->where('melis_cms_page_published.page_status=1');
		
		$columns = $this->aliasColumnsFromTableDefinition('MelisEngine\MelisPageColumns', 's_');
		if ($publishedOnly == 0)
			$select->join('melis_cms_page_saved', 'melis_cms_page_saved.page_id = melis_cms_page_tree.tree_page_id',
					$columns, $select::JOIN_LEFT);
		
		$select->where('tree_father_page_id = ' . $id);
		$select->order('tree_page_order ASC');
		
		$resultSet = $this->tableGateway->selectWith($select);
		
		return $resultSet;
	}
	
	public function getFatherPageById($id)
	{
		$select = $this->tableGateway->getSql()->select();
		
		$select->columns(array('*'));
		
		$select->join('melis_cms_page_lang', 'melis_cms_page_lang.plang_page_id = melis_cms_page_tree.tree_father_page_id', 
						array('plang_lang_id'));
		$select->join('melis_cms_lang', 'melis_cms_lang.lang_cms_id = melis_cms_page_lang.plang_lang_id', 
						array('*'), $select::JOIN_LEFT);
		
		$select->join('melis_cms_page_published', 'melis_cms_page_published.page_id = melis_cms_page_tree.tree_father_page_id', 
						array('*'), $select::JOIN_LEFT);

		$columns = $this->aliasColumnsFromTableDefinition('MelisEngine\MelisPageColumns', 's_');
		$select->join('melis_cms_page_saved', 'melis_cms_page_saved.page_id = melis_cms_page_tree.tree_father_page_id', 
						$columns, $select::JOIN_LEFT);
		
		$select->where('tree_page_id = ' . $id);
		
		$resultSet = $this->tableGateway->selectWith($select);
		
		return $resultSet;
	}
}
