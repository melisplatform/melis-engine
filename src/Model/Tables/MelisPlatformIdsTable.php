<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

class MelisPlatformIdsTable extends MelisGenericTable
{
	public function __construct(TableGateway $tableGateway)
	{
		parent::__construct($tableGateway);
		$this->idField = 'pids_id';
	}

	/**
	 * Gets the platform ids of a specific platform
	 * 
	 * @param string $platformName
	 */
	public function getPlatformIdsByPlatformName($platformName)
	{
		$select = $this->tableGateway->getSql()->select();
		$select->columns(array('*'));
		$select->join('melis_core_platform', 'melis_core_platform.plf_id = melis_cms_platform_ids.pids_id',
				array('*'));
		$select->where("plf_name = '$platformName'");
		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}
	
	/*
	 * Checking if a range of ids is already used
	 * 
	 * @param int $pageMinValue
	 * @param int $pageMaxValue
	 * @param int $tplMinValue
	 * @param int $tplMaxValue
	 * @param int $pids_id current platform id
	 * 
	 * @return boolean
	 */
	public function platformIdsRangeIsExist($pageMinValue, $pageMaxValue, $tplMinValue, 
	                                        $tplMaxValue, $pids_id)
	{
	    $select = $this->tableGateway->getSql()->select();
	    $select->where->between('pids_page_id_start', $pageMinValue, $pageMaxValue);
	    $select->where->OR->between('pids_page_id_end', $pageMinValue, $pageMaxValue);
        
	    $select->where->OR->between('pids_tpl_id_start', $tplMinValue, $tplMaxValue);
	    $select->where->OR->between('pids_tpl_id_end', $tplMinValue, $tplMaxValue);
	    
	    
	    $resultSet = $this->tableGateway->selectWith($select);
	    if (!empty($resultSet))
	    {
	        $result = $resultSet->toArray();
	        if (!empty($result))
	        {
	            // Checking the Number of Rows
	            if (count($result)==1)
	            {
	                // Checking if the ID is the current data that using updating
	                if ($result[0]['pids_id']==$pids_id)
	                    return true;
	            }
	        }
	        else
	            return true;
	    }
	    
	    return false;
	}
	
	/*
	 * Fetching the Available Platform that not exist to the CMS Platform IDs.
	 * @return Array
	 */
	public function getAvailablePlatforms()
	{
	    $select = $this->tableGateway->getSql()->select();
	    $select->columns(array('*'));
	    $select->join('melis_core_platform', 'melis_core_platform.plf_id = melis_cms_platform_ids.pids_id', array('*'), $select::JOIN_RIGHT);
	    $select->where('melis_cms_platform_ids.pids_id IS NULL');
	    $resultSet = $this->tableGateway->selectWith($select);
	    
	    return $resultSet;
	}
}
