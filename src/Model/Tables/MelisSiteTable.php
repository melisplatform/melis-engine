<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Tables;

use Laminas\Db\TableGateway\TableGateway;
use Laminas\Db\Sql\Where;
use Laminas\Db\Sql\Predicate\PredicateSet;
use Laminas\Db\Sql\Predicate\Like;
use Laminas\Db\Sql\Predicate\Operator;
use Laminas\Db\Sql\Predicate\Expression;

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
	public function getSiteById($idSite, $env, $includeSite404Table = false)
	{
        // Retrieve cache version if front mode to avoid multiple calls
	    $cacheKey = get_class($this) . '_getSiteById_' . $idSite . '_' . $env . '_' . $includeSite404Table;
        $cacheConfig = 'engine_page_services';
        $melisEngineCacheSystem = $this->getServiceLocator()->get('MelisEngineCacheSystem');
        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
        if (!empty($results)) return $results;
	    
		$select = $this->tableGateway->getSql()->select();
		
		$select->columns(array('*'));
	
		$siteDomainjoin = new Expression('melis_cms_site_domain.sdom_site_id = melis_cms_site.site_id AND sdom_env =\''.$env.'\'');
		
		if($includeSite404Table) {
		    
		    $site404join = new Expression('melis_cms_site_domain.sdom_site_id = melis_cms_site.site_id');
		    
		    $select->join('melis_cms_site_domain', $siteDomainjoin, array('*'), $select::JOIN_LEFT)
		           ->join('melis_cms_site_404', $site404join, array('*'), $select::JOIN_LEFT);
		}
		else {
		    $select->join('melis_cms_site_domain', $siteDomainjoin, array('*'), $select::JOIN_LEFT);
		}
		
		$select->where('site_id ='.$idSite);
	
		$resultSet = $this->tableGateway->selectWith($select);

		$melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $resultSet);
		
		return $resultSet;
	}

    /**
     * @param string $search
     * @param array $searchableColumns
     * @param string $orderBy
     * @param string $orderDirection
     * @param int $start
     * @param null $limit
     * @return null|\Laminas\Db\ResultSet\ResultSetInterface
     */
	public function getSitesData($search = '', $searchableColumns = [], $orderBy = '', $orderDirection = 'ASC', $start = 0, $limit = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array('*'));
        $select->join('melis_cms_site_langs', 'melis_cms_site_langs.slang_site_id = melis_cms_site.site_id',array(), $select::JOIN_LEFT)
                ->join('melis_cms_lang', 'melis_cms_lang.lang_cms_id = melis_cms_site_langs.slang_lang_id', array('site_langs' => new Expression('GROUP_CONCAT(if(melis_cms_site_langs.slang_status, melis_cms_lang.lang_cms_name, null) SEPARATOR \', \')')), $select::JOIN_LEFT);

        if(!empty($searchableColumns) && !empty($search)) {
            foreach($searchableColumns as $column) {
                $select->having->or->like($column, '%'.$search.'%');
            }
        }

        if(!empty($orderBy)) {
            $select->order($orderBy . ' ' . $orderDirection);
        }

        $getCount = $this->tableGateway->selectWith($select);
        // set current data count for pagination
        $this->setCurrentDataCount((int) $getCount->count());

        if(!empty($limit)) {
            $select->limit($limit);
        }

        if(!empty($start)) {
            $select->offset($start);
        }

        $select->group('site_id');

        $sql = $this->tableGateway->getSql();
        $raw = $sql->getSqlstringForSqlObject($select);

        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;

    }
    
    public function getSiteSavedPagesById($siteId)
    {
        $select = $this->tableGateway->getSql()->select();
        
        $select->columns(array());
        $select->join('melis_cms_template', 'melis_cms_template.tpl_site_id = melis_cms_site.site_id',array('*'), $select::JOIN_LEFT)
                ->join('melis_cms_page_saved', 'melis_cms_page_saved.page_tpl_id = melis_cms_template.tpl_id', array('*'), $select::JOIN_LEFT);
        
        $select->where('melis_cms_template.tpl_site_id ='.$siteId);
        
        $resultSet = $this->tableGateway->selectWith($select);
        return $resultSet;
    }
}
