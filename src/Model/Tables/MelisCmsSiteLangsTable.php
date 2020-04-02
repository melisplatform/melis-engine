<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2019 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables;

use Laminas\Db\TableGateway\TableGateway;

class MelisCmsSiteLangsTable extends MelisGenericTable
{
	public function __construct(TableGateway $tableGateway)
	{
		parent::__construct($tableGateway);
		$this->idField = 'slang_id';
	}

    public function getSiteLanguagesBySiteId($siteId, $isActive = true)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->join(array('cmsLang' => 'melis_cms_lang'), 'cmsLang.lang_cms_id = melis_cms_site_langs.slang_lang_id', array('*'), $select::JOIN_LEFT);
        $select->where->equalTo("melis_cms_site_langs.slang_site_id", $siteId);
        $select->where->equalTo("melis_cms_site_langs.slang_status", $isActive);

        $data = $this->tableGateway->selectWith($select);
        return $data;
    }

	public function getSiteLangs ($siteLangId, $siteId, $langId, $isActive)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['*']);
        $select->join(
            'melis_cms_lang',
            'melis_cms_site_langs.slang_lang_id = melis_cms_lang.lang_cms_id',
            [
                'lang_cms_name',
                'lang_cms_locale'
            ]
        );

        if (!empty($siteLangId) && !is_null($siteLangId)) {
            $select->where->equalTo('slang_id', $siteLangId);
        }

        if (!empty($siteId) && !is_null($siteId)) {
            $select->where->equalTo('slang_site_id', $siteId);
        }

        if (isset($langId)) {
            $select->where->equalTo('slang_lang_id', $langId);
        }

        if (isset($isActive)) {
            $select->where->equalTo('slang_status', $isActive);
        }

        $resultSet = $this->tableGateway->selectWith($select);

        return $resultSet;
    }
}
