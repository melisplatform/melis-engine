<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables;

use Laminas\Db\TableGateway\TableGateway;

class MelisPagePublishedTable extends MelisGenericTable
{
    /**
     * Model table
     */
    const TABLE = 'melis_cms_page_published';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'page_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    public function getPublishedSitePagesById($pageId)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->where('page_status = 1');
        $select->where(array('page_type' => array('SITE', 'PAGE')));
        $select->where('page_id =' . (int)$pageId);
        $resultSet = $this->tableGateway->selectWith($select);

        return $resultSet;
    }

    /**
     * Retrieve pages by type & per site
     *
     * @param string|null $pageType
     * @param int|null $siteId
     * @return \Laminas\Db\ResultSet\ResultSetInterface
     */
    public function getPagesByType(string $pageType = null, int $siteId = null)
    {
        $select = $this->tableGateway->getSql()->select();

        if (!empty($pageType)) {
            $select->where->equalTo('page_type', $pageType);
        }

        if (!empty($siteId)) {
            $select->join(
                'melis_cms_template',
                'melis_cms_template.tpl_id = melis_cms_page_published.page_tpl_id',
                [
                    'tpl_id',
                    'tpl_site_id',
                    'tpl_zf2_website_folder',
                ],
                $select::JOIN_LEFT
            );
            $select->where->equalTo('tpl_site_id', $siteId);
        }

        return $this->tableGateway->selectWith($select);
    }
}
