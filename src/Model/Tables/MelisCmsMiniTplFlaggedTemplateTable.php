<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2019 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables;

use MelisCore\Model\Tables\MelisGenericTable;

// this table will record the name of the default templates from Modules and considered them as flagged when they are updated
//they wont be displayed anymore in the minitemplate listing/options since they now have a copy of the updated template inside the public folder in the roor directory
class MelisCmsMiniTplFlaggedTemplateTable extends MelisGenericTable
{
    /**
     * Model table
     */
    const TABLE = 'melis_cms_mini_tpl_flagged_template';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'mtpft_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    public function getFlaggedTemplate($moduleName = null, $templateName = null)
    {
        $select = $this->tableGateway->getSql()->select();

        if (!empty($templateName)) {
            $select->where->equalTo("melis_cms_mini_tpl_flagged_template.mtpft_template_name", $templateName);
        }

        if (!empty($moduleName)) {
            $select->where->equalTo("melis_cms_mini_tpl_flagged_template.mtpft_template_module", $moduleName);
        }

        $data = $this->tableGateway->selectWith($select);
        return $data;
    }
}
