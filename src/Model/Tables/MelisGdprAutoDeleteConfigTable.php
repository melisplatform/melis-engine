<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2019 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables;


use Zend\Db\TableGateway\TableGateway;

class MelisGdprAutoDeleteConfigTable  extends MelisGenericTable
{
    protected $tableGateway;
    protected $idField;

    public function __construct(TableGateway $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->idField = 'mgdprc_id';
    }

    /**
     * get auto delete config by site id and module
     *
     * @param $siteId
     * @param $module
     */
    public function getAutoDeleteConfig($siteId, $module)
    {

        $select = $this->tableGateway->getSql()->select();

        if (! empty($siteId)) {
            $select->where->equalTo('mgdprc_site_id', $siteId);
        }

        if (! empty($module)) {
            $select->where->equalTo('mgdprc_module_name', $module);
        }

        return $this->tableGateway->selectWith($select);
    }
}
