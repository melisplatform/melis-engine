<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2019 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables;

use Laminas\Db\TableGateway\TableGateway;

class MelisCmsSiteHomeTable extends MelisGenericTable
{
	public function __construct(TableGateway $tableGateway)
	{
		parent::__construct($tableGateway);
		$this->idField = 'shome_id';
	}

	public function getHomePageBySiteIdAndLangId($siteId, $langId)
    {
        $select = $this->tableGateway->getSql()->select();

        if (!empty($siteId) && !is_null($siteId)) {
            $select->where->equalTo("melis_cms_site_home.shome_site_id", $siteId);
        }

        if (!empty($langId) && !is_null($langId)) {
            $select->where->equalTo("melis_cms_site_home.shome_lang_id", $langId);
        }

        $data = $this->tableGateway->selectWith($select);

        return $data;
    }

    public function getHomePageBySiteId($siteId)
    {
        $select = $this->tableGateway->getSql()->select();

        if (!empty($siteId) && !is_null($siteId)) {
            $select->where->equalTo("melis_cms_site_home.shome_site_id", $siteId);
        }
        
        $data = $this->tableGateway->selectWith($select);

        return $data;
    }

    public function deleteHomePageId($id, $siteId, $langId, $pageId)
    {
        $delete = $this->tableGateway->getSql()->delete(null);

        if ($id) {
            $delete->where->equalTo('shome_id', $id);
        }

        if ($siteId) {
            $delete->where->equalTo('shome_site_id', $siteId);
        }

        if ($langId) {
            $delete->where->equalTo('shome_lang_id', $langId);
        }

        if ($pageId) {
            $delete->where->equalTo('shome_page_id', $pageId);
        }

        return $this->tableGateway->deleteWith($delete);
    }
}
