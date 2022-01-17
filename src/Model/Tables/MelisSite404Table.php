<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables;

use Laminas\Db\TableGateway\TableGateway;

class MelisSite404Table extends MelisGenericTable
{
    /**
     * Model table
     */
    const TABLE = 'melis_cms_site_404';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 's404_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

	/**
	 * Gets the 404 for a siteId and main page_id
	 * 
	 * @param int $siteId
	 * @param int $pageId
	 */
	public function getDataBySiteIdAndPageId($siteId, $pageId)
	{
	    $select = $this->tableGateway->getSql()->select();
	     
	    $select->columns(array('*'));
	     
	    $select->where(array("s404_site_id" => $siteId, 's404_page_id' => $pageId));
	    $resultSet = $this->tableGateway->selectWith($select);
	     
	    return $resultSet;
	}

	/**
	 * Gets the 404 page given the siteId and langId
	 * 
	 * @param int $siteId
	 * @param int $langId
	 */
	public function get404PageBySiteIdAndLangId($siteId, $langId)
    {
        $select = $this->tableGateway->getSql()->select();

        if (!empty($siteId) && !is_null($siteId)) {
            $select->where->equalTo("melis_cms_site_404.s404_site_id", $siteId);
        }

        if (!empty($langId) && !is_null($langId)) {
            $select->where->equalTo("melis_cms_site_404.s404_lang_id", $langId);
        }

        $data = $this->tableGateway->selectWith($select);

        return $data;
    }

}
