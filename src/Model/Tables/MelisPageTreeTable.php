<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables;

use Laminas\Db\TableGateway\TableGateway;

class MelisPageTreeTable extends MelisGenericTable
{
	/**
	 * Model table
	 */
	const TABLE = 'melis_cms_page_tree';

	/**
	 * Table primary key
	 */
	const PRIMARY_KEY = 'tree_page_id';

	public function __construct()
	{
		$this->idField = self::PRIMARY_KEY;
	}

	/**
	 * Gets full datas for a page from tree, page saved, paged published, lang
	 * @param int $id PageId
	 * @param string $type saved or published to limit
	 */
	public function getFullDatasPage($id, $type = '')
	{

		$select = $this->tableGateway->getSql()->select();

		$select->columns(array('*'));

		$select->join(
			'melis_cms_page_lang',
			'melis_cms_page_lang.plang_page_id = melis_cms_page_tree.tree_page_id',
			array('plang_lang_id')
		);
		$select->join(
			'melis_cms_lang',
			'melis_cms_lang.lang_cms_id = melis_cms_page_lang.plang_lang_id',
			array('*'),
			$select::JOIN_LEFT
		);
		$select->join('melis_cms_page_style', 'melis_cms_page_tree.tree_page_id = melis_cms_page_style.pstyle_page_id', array(), $select::JOIN_LEFT);

		$select->join('melis_cms_style', ' melis_cms_style.style_id = melis_cms_page_style.pstyle_style_id', array('*'), $select::JOIN_LEFT);

		if ($type == 'published' || $type == '')
			$select->join(
				'melis_cms_page_published',
				'melis_cms_page_published.page_id = melis_cms_page_tree.tree_page_id',
				array('*'),
				$select::JOIN_LEFT
			);

		if ($type == 'saved')
			$select->join(
				'melis_cms_page_saved',
				'melis_cms_page_saved.page_id = melis_cms_page_tree.tree_page_id',
				array('*'),
				$select::JOIN_LEFT
			);

		if ($type == '') {
			$columns = $this->aliasColumnsFromTableDefinition('MelisEngine\MelisPageColumns', 's_');
			$select->join(
				'melis_cms_page_saved',
				'melis_cms_page_saved.page_id = melis_cms_page_tree.tree_page_id',
				$columns,
				$select::JOIN_LEFT
			);
		}

		$select->join(
			'melis_cms_page_seo',
			'melis_cms_page_seo.pseo_id = melis_cms_page_tree.tree_page_id',
			array('*'),
			$select::JOIN_LEFT
		);

		$select->where('tree_page_id = ' . (int)$id);

		$resultSet = $this->tableGateway->selectWith($select);

		return $resultSet;
	}

	/**
	 * Gets the children of a page
	 * 
	 * @param int $id parent page id
	 * @param int $publishedOnly If true, only published children page will be brought back
	 */
	public function getPageChildrenByidPage($id, $publishedOnly = 0)
	{
		$select = $this->tableGateway->getSql()->select();

		$select->columns(array('*'));

		$select->join(
			'melis_cms_page_lang',
			'melis_cms_page_lang.plang_page_id = melis_cms_page_tree.tree_page_id',
			array('plang_lang_id')
		);
		$select->join(
			'melis_cms_lang',
			'melis_cms_lang.lang_cms_id = melis_cms_page_lang.plang_lang_id',
			array('*'),
			$select::JOIN_LEFT
		);

		$select->join(
			'melis_cms_page_published',
			'melis_cms_page_published.page_id = melis_cms_page_tree.tree_page_id',
			array('*'),
			$select::JOIN_LEFT
		);

		$select->join('melis_cms_page_style', 'melis_cms_page_tree.tree_page_id = melis_cms_page_style.pstyle_page_id', array(), $select::JOIN_LEFT);

		$select->join('melis_cms_style', ' melis_cms_style.style_id = melis_cms_page_style.pstyle_style_id', array('*'), $select::JOIN_LEFT);

		if ($publishedOnly == 1)
			$select->where('melis_cms_page_published.page_status=1');

		if ($publishedOnly == 0) {
			$columns = $this->aliasColumnsFromTableDefinition('MelisEngine\MelisPageColumns', 's_');
			$select->join(
				'melis_cms_page_saved',
				'melis_cms_page_saved.page_id = melis_cms_page_tree.tree_page_id',
				$columns,
				$select::JOIN_LEFT
			);
		}

		$select->join(
			'melis_cms_page_seo',
			'melis_cms_page_seo.pseo_id = melis_cms_page_tree.tree_page_id',
			array('*'),
			$select::JOIN_LEFT
		);

		$select->join(
			'melis_cms_page_default_urls',
			'melis_cms_page_default_urls.purl_page_id = melis_cms_page_tree.tree_page_id',
			array('*'),
			$select::JOIN_LEFT
		);

		$select->where('tree_father_page_id = ' . (int)$id);
		$select->order('tree_page_order ASC');

		$resultSet = $this->tableGateway->selectWith($select);

		return $resultSet;
	}

	/**
	 * This function brings back the father page of a page
	 * 
	 * @param int $id Page id to look for
	 */
	public function getFatherPageById($id, $type = 'published')
	{
		$select = $this->tableGateway->getSql()->select();

		$select->columns(array('*'));

		$select->join(
			'melis_cms_page_lang',
			'melis_cms_page_lang.plang_page_id = melis_cms_page_tree.tree_father_page_id',
			array('plang_lang_id')
		);
		$select->join(
			'melis_cms_lang',
			'melis_cms_lang.lang_cms_id = melis_cms_page_lang.plang_lang_id',
			array('*'),
			$select::JOIN_LEFT
		);

		$select->join(
			'melis_cms_page_published',
			'melis_cms_page_published.page_id = melis_cms_page_tree.tree_father_page_id',
			array('*'),
			$select::JOIN_LEFT
		);

		$select->join('melis_cms_page_style', 'melis_cms_page_tree.tree_page_id = melis_cms_page_style.pstyle_page_id', array(), $select::JOIN_LEFT);

		$select->join('melis_cms_style', ' melis_cms_style.style_id = melis_cms_page_style.pstyle_style_id', array('*'), $select::JOIN_LEFT);

		if ($type != 'published' || $type == '') {
			$columns = $this->aliasColumnsFromTableDefinition('MelisEngine\MelisPageColumns', 's_');
			$select->join(
				'melis_cms_page_saved',
				'melis_cms_page_saved.page_id = melis_cms_page_tree.tree_father_page_id',
				$columns,
				$select::JOIN_LEFT
			);
		}

		$select->join(
			'melis_cms_page_seo',
			'melis_cms_page_seo.pseo_id = melis_cms_page_tree.tree_father_page_id',
			array('*'),
			$select::JOIN_LEFT
		);

		$select->where('tree_page_id = ' . (int)$id);

		$resultSet = $this->tableGateway->selectWith($select);

		return $resultSet;
	}

	public function getPageTreeOrderedByFatherId($fatherId)
	{
		$select = $this->tableGateway->getSql()->select();

		$select->columns(array('*'));
		$select->where('tree_father_page_id =' . (int)$fatherId);
		$select->order(array('tree_page_order' => 'ASC'));

		$resultSet = $this->tableGateway->selectWith($select);

		return $resultSet;
	}

	/**
	 * This function searches for page name or page id
	 * 
	 * @param string $value The search value
	 * @param string $type saved or published to limit
	 * 
	 * returns resultset page ids
	 */
	public function getPagesBySearchValue($value, $type = '')
	{
		$select = $this->tableGateway->getSql()->select();
		$select->columns(array('tree_page_id'));

		if ($type == 'published' || $type == '') {
			$select->join(
				'melis_cms_page_published',
				'melis_cms_page_published.page_id = melis_cms_page_tree.tree_page_id',
				array(),
				$select::JOIN_LEFT
			);
		}


		if ($type == 'saved') {
			$select->join(
				'melis_cms_page_saved',
				'melis_cms_page_saved.page_id = melis_cms_page_tree.tree_page_id',
				array(),
				$select::JOIN_LEFT
			);
		}

		$select->join('melis_cms_page_style', 'melis_cms_page_tree.tree_page_id = melis_cms_page_style.pstyle_page_id', array(), $select::JOIN_LEFT);

		$select->join('melis_cms_style', ' melis_cms_style.style_id = melis_cms_page_style.pstyle_style_id', array('*'), $select::JOIN_LEFT);

		$search = '%' . $value . '%';
		$select->where->NEST->like('page_name', $search)
			->or->like('melis_cms_page_tree.tree_page_id', $search);

		$resultSet = $this->tableGateway->selectWith($select);

		return $resultSet;
	}

	public function getPageTreeByFatherIdWithDetails($fatherId)
	{
		$select = $this->tableGateway->getSql()->select();

		$select->columns(array('*'));

		$select->join(
			'melis_cms_page_lang',
			'melis_cms_page_lang.plang_page_id = melis_cms_page_tree.tree_page_id',
			array('plang_lang_id')
		);
		$select->join(
			'melis_cms_lang',
			'melis_cms_lang.lang_cms_id = melis_cms_page_lang.plang_lang_id',
			array('*'),
			$select::JOIN_LEFT
		);
		$select->join('melis_cms_page_style', 'melis_cms_page_tree.tree_page_id = melis_cms_page_style.pstyle_page_id', array(), $select::JOIN_LEFT);

		$select->join('melis_cms_style', ' melis_cms_style.style_id = melis_cms_page_style.pstyle_style_id', array('*'), $select::JOIN_LEFT);

		$select->join(
			'melis_cms_page_saved',
			'melis_cms_page_saved.page_id = melis_cms_page_tree.tree_page_id',
			array('*'),
			$select::JOIN_LEFT
		);

		$select->join(
			'melis_cms_page_seo',
			'melis_cms_page_seo.pseo_id = melis_cms_page_tree.tree_page_id',
			array('*'),
			$select::JOIN_LEFT
		);

		$select->where('tree_father_page_id =' . (int)$fatherId);
		$select->order('tree_page_order ASC');

		$sql = $this->tableGateway->getSql();
		$resultSet = $this->tableGateway->selectWith($select);

		return $resultSet;
	}
}
