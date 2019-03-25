<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2019 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

class MelisCmsSiteLangsTable extends MelisGenericTable
{
	public function __construct(TableGateway $tableGateway)
	{
		parent::__construct($tableGateway);
		$this->idField = 'slang_id';
	}

    public function getSiteLanguagesBySiteId($siteId){
        $select = $this->tableGateway->getSql()->select();
        $select->join(array('cmsLang'=>'melis_cms_lang'), 'cmsLang.lang_cms_id = melis_cms_site_langs.slang_lang_id', array('*'), $select::JOIN_LEFT);
        $select->where->equalTo("melis_cms_site_langs.slang_site_id", $siteId);

        $data = $this->tableGateway->selectWith($select);
        return $data;
    }
}
