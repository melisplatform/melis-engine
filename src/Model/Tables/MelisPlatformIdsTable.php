<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables;

use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Db\Sql\Where;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Db\Sql\Expression;
use Laminas\Hydrator\ObjectPropertyHydrator;
use MelisEngine\Model\Hydrator\MelisPlatformIds;

class MelisPlatformIdsTable extends MelisGenericTable
{
    /**
     * Model table
     */
    const TABLE = 'melis_cms_platform_ids';

    /**
     * Table primary key
     */
    const PRIMARY_KEY = 'pids_id';

    public function __construct()
    {
        $this->idField = self::PRIMARY_KEY;
    }

    /**
     * @return HydratingResultSet
     */
    public function hydratingResultSet()
    {
        return $hydratingResultSet = new HydratingResultSet(new ObjectPropertyHydrator(), new MelisPlatformIds());
    }

    /**
     * @param array $options
     * @return \Laminas\Db\ResultSet\ResultSetInterface
     */
    public function getData(array $options = [])
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(['*']);

        $select->join(
            'melis_core_platform',
            'melis_core_platform.plf_id = melis_cms_platform_ids.pids_id',
            ['*'],
            $select::JOIN_LEFT
        );

        /** Get "unfiltered" data */
        $unfilteredData = $this->tableGateway->selectWith($select);

        if (!empty($options['limit'])) {
            $select->limit($options['limit']);
        }

        if (!empty($options['start'])) {
            $select->offset($options['start']);
        }

        if (!empty($options['order']['key']) && !empty($options['order']['dir'])) {
            if ($options['order']['key'] == 'pids_name') {
                $select->order('melis_core_platform.plf_name ' . $options['order']['dir']);
            } else {
                $select->order($options['order']['key'] . ' ' . $options['order']['dir']);
            }
        }

        $resultSet = $this->tableGateway->selectWith($select);
        $resultSet->getObjectPrototype()->setFilteredDataCount($resultSet->count());
        $resultSet->getObjectPrototype()->setUnfilteredDataCount($unfilteredData->count());

        return $resultSet;
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
	
	/**
	 * Fetches the an entry with the highes range of page id end and pids tpl id end
	 */
	public function getLastPlatformRange()
	{
	    
	    $select = $this->tableGateway->getSql()->select();
	    
	    $select->columns(array(
           'pids_page_id_end_max' => new Expression('max(pids_page_id_end)'),
           'pids_tpl_id_end_max' => new Expression('max(pids_tpl_id_end)'),
           )
        );
	    
	    $resultSet = $this->tableGateway->selectWith($select);
	    
	    return $resultSet;
	}
}
