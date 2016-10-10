<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables;

use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Where;
use Zend\Db\Sql\Predicate\PredicateSet;
use Zend\Db\Sql\Predicate\Like;
use Zend\Db\Sql\Predicate\Operator;

class MelisSiteTable extends MelisGenericTable
{
	public function __construct(TableGateway $tableGateway)
	{
		parent::__construct($tableGateway);
		$this->idField = 'site_id';
	}
	
	/**
	 * Gets all the datas for a site
	 * 
	 * @param string $env MELIS_PLATFORM variable
	 * @param boolean $includeSite404Table If true, gets the datas in 404 table
	 */
	public function getSites($env = '', $includeSite404Table = false)
	{
		$select = $this->tableGateway->getSql()->select();
		
		$select->columns(array('*'));
	
		if($includeSite404Table) {
		    $select->join('melis_cms_site_domain', 'melis_cms_site_domain.sdom_site_id = melis_cms_site.site_id',
		        array('*'), $select::JOIN_LEFT)
		        ->join('melis_cms_site_404', 'melis_cms_site_404.s404_site_id = melis_cms_site.site_id',
		            array('*'), $select::JOIN_LEFT);
		}
		else {
		    $select->join('melis_cms_site_domain', 'melis_cms_site_domain.sdom_site_id = melis_cms_site.site_id',
		        array('*'), $select::JOIN_LEFT);
		}


		if ($env != '')
			$select->where("sdom_env = '$env'");
	
		$resultSet = $this->tableGateway->selectWith($select);
	
		return $resultSet;
	}
	
	/**
	 * Gets a site by its id, and can restrict to an environment for the domain, and 404 datas
	 * 
	 * @param int $idSite
	 * @param string $env
	 * @param boolean $includeSite404Table
	 */
	public function getSiteById($idSite, $env = '', $includeSite404Table = false)
	{
		$select = $this->tableGateway->getSql()->select();
		
		$select->columns(array('*'));
	
		if($includeSite404Table) {
		    $select->join('melis_cms_site_domain', 'melis_cms_site_domain.sdom_site_id = melis_cms_site.site_id',
		        array('*'), $select::JOIN_LEFT)
		        ->join('melis_cms_site_404', 'melis_cms_site_404.s404_site_id = melis_cms_site.site_id',
		            array('*'), $select::JOIN_LEFT);
		}
		else {
    		$select->join('melis_cms_site_domain', 'melis_cms_site_domain.sdom_site_id = melis_cms_site.site_id',
    				array('*'), $select::JOIN_LEFT);
		}
	
        $select->where("melis_cms_site_domain.sdom_site_id = " . $idSite . ' AND melis_cms_site_domain.sdom_env=\''.$env.'\'');
		$resultSet = $this->tableGateway->selectWith($select);
		
		$sql = $this->tableGateway->getSql();
		$raw = $sql->getSqlstringForSqlObject($select);
		//echo $raw;
		return $resultSet;
	}
	
	/**
	 * Gets the list of sites
	 * 
	 * @param array $options
	 * @param unknown $fixedCriteria
	 */
	public function getSitesData(array $options, $fixedCriteria = null) {
	    
	    $select = $this->tableGateway->getSql()->select();
	    
	    $select->columns(array('*'));
	    $select->join('melis_cms_site_domain', 'melis_cms_site_domain.sdom_site_id = melis_cms_site.site_id',
	        array('*'), $select::JOIN_LEFT)
	        ->join('melis_cms_site_404', 'melis_cms_site_404.s404_site_id = melis_cms_site.site_id',
	            array('*'), $select::JOIN_LEFT);
	    
        $select->group('melis_cms_site.site_id');
	    
	    $where = !empty($options['where']['key']) ? $options['where']['key'] : '';
	    $whereValue = !empty($options['where']['value']) ? $options['where']['value'] : '';
	    
	    $order = !empty($options['order']['key']) ? $options['order']['key'] : '';
	    $orderDir = !empty($options['order']['dir']) ? $options['order']['dir'] : 'ASC';
	    
	    $start = (int) $options['start'];
	    $limit = (int) $options['limit'] === -1 ? $this->getTotalData() : (int) $options['limit'];
	    
	    $columns = $options['columns'];
	     
	    // check if there's an extra variable that should be included in the query
	    $dateFilter = $options['date_filter'];
	    $dateFilterSql = '';
	     
	    if(count($dateFilter)) {
	        if(!empty($dateFilter['startDate']) && !empty($dateFilter['endDate'])) {
	            $dateFilterSql = '`' . $dateFilter['key'] . '` BETWEEN \'' . $dateFilter['startDate'] . '\' AND \'' . $dateFilter['endDate'] . '\'';
	        }
	    }
	    
	    // this is used when searching
	    if(!empty($where)) {
	        $w = new Where();
	        $p = new PredicateSet();
	        $filters = array();
	        $likes = array();
	        foreach($columns as $colKeys)
	        {
	            $likes[] = new Like($colKeys, '%'.$whereValue.'%');
	        }
	         
	        if(!empty($dateFilterSql))
	        {
	            $filters = array(new PredicateSet($likes,PredicateSet::COMBINED_BY_OR), new \Zend\Db\Sql\Predicate\Expression($dateFilterSql));
	        }
	        else
	        {
	            $filters = array(new PredicateSet($likes,PredicateSet::COMBINED_BY_OR));
	        }
	        $fixedWhere = array(new PredicateSet(array(new Operator('', '=', ''))));
	        if(is_null($fixedCriteria))
	        {
	            $select->where($filters);
	        }
	        else
	        {
	            $select->where(array(
	                $fixedWhere,
	                $filters,
	            ), PredicateSet::OP_AND);
	        }
	         
	    }
	     
	    
	    // used when column ordering is clicked
	    if(!empty($order))
	        $select->order($order . ' ' . $orderDir);
	    
	         
	        $test = $this->tableGateway->selectWith($select);
	        $this->setCurrentDataCount((int) $test->count());
	         
	        // this is used in paginations
	        $select->limit($limit);
	        $select->offset($start);
	        

	    
	        $resultSet = $this->tableGateway->selectWith($select);
	    
	        $sql = $this->tableGateway->getSql();
	        $raw = $sql->getSqlstringForSqlObject($select);
	        
	        return $resultSet;
	    
	}
}
