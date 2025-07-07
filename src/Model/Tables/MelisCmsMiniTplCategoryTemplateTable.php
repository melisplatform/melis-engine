<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2019 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables;

use MelisCore\Model\Tables\MelisGenericTable;
use Laminas\Db\TableGateway\TableGateway;

class MelisCmsMiniTplCategoryTemplateTable extends MelisGenericTable
{
    /**
     * Model table
     */
    const TABLE = 'melis_cms_mini_tpl_category_template';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'mtplct_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    public function getTemplateBySiteId($site_id, $template_name)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['*']);
        $select->where->equalTo('mtplct_site_id', $site_id);
        $select->where->equalTo('mtplct_template_name', $template_name);
        return $this->tableGateway->selectWith($select);
    }

    public function getTemplatesByCategoryIds($cat_ids = [], $site_id = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['*']);
        $select->join('melis_cms_site', "melis_cms_site.site_id = " . self::TABLE . ".mtplct_site_id", 'site_label', 'left');
        $select->where->in('mtplct_category_id', $cat_ids);

        if (! empty($site_id))
            $select->where->equalTo('mtplct_site_id', $site_id);

        $select->order('mtplct_order ASC');
        return $this->tableGateway->selectWith($select);
    }

    public function getAffectedMiniTemplates($order)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['*']);
        $select->where->greaterThanOrEqualTo('mtplct_order', $order);
        $select->order('mtplct_order ASC');
        return $this->tableGateway->selectWith($select);
    }

    public function getLatestOrder($cat_id)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['*']);
        $select->where->equalTo('mtplct_category_id', $cat_id);
        $select->order('mtplct_order DESC');
        $select->limit(1);
        return $this->tableGateway->selectWith($select);
    }

    public function updateMiniTemplate($data, $site_id, $template_name)
    {
        $update = $this->tableGateway->getSql()->update();
        $update->set($data);
        if (! empty($site_id))
            $update->where->equalTo('mtplct_site_id', $site_id);
        if (! empty($template_name))
            $update->where->equalTo('mtplct_template_name', $template_name);
        $resultSet = $this->tableGateway->updateWith($update);
    }

    public function deletePluginFromCategory($siteId, $template)
    {
        $delete = $this->tableGateway->getSql()->delete();
        $delete->where->equalTo('mtplct_site_id', $siteId);
        $delete->where->equalTo('mtplct_template_name', $template);
        return $this->tableGateway->deleteWith($delete);
    }
}
