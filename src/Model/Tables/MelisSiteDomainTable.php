<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables;

use Zend\Db\TableGateway\TableGateway;

class MelisSiteDomainTable extends MelisGenericTable
{
	public function __construct(TableGateway $tableGateway)
	{
		parent::__construct($tableGateway);
		$this->idField = 'sdom_id';
		$this->cacheResults = true;
	}
	
	/**
	 * Gets the domain by the site id and the environment platform
	 * 
	 * @param int $siteId
	 * @param string $siteEnv
	 */
	public function getDataBySiteIdAndEnv($siteId, $siteEnv) 
	{
	    $select = $this->tableGateway->getSql()->select();
	    
	    $select->columns(array('*'));
	    
	    $select->where(array("sdom_site_id" => $siteId, 'sdom_env' => $siteEnv));
	    $resultSet = $this->tableGateway->selectWith($select);
	    
	    return $resultSet;
	}
	
	/**
	 * Get the domain of the site per its environement
	 * 
	 * @param string $siteEnv
	 */
	public function getDataByEnv($siteEnv) 
	{
	    $select = $this->tableGateway->getSql()->select();
	     
	    $select->columns(array('*'));
	    $select->group('melis_cms_site_domain.sdom_env');
	    $select->where(array('sdom_env' => $siteEnv));
	    $resultSet = $this->tableGateway->selectWith($select);
	     
	    return $resultSet;
	}

	public function getSitesByEnv($env = null)
    {
        $select = $this->tableGateway->getSql()->select();

        $select->columns(array('*'));

        if(is_null($env) && !empty($env)) {
            $select->where->equalTo('sdom_env', $env);
        }

        $select->group('sdom_env');

        $resultSet = $this->tableGateway->selectWith($select);

        return $resultSet;
    }
}
