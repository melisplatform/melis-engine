<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables;

use Laminas\Db\TableGateway\TableGateway;

class MelisPageStyleTable extends MelisGenericTable
{
    /**
     * Model table
     */
    const TABLE = 'melis_cms_page_style';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'pstyle_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
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
