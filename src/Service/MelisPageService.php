<?php

namespace MelisEngine\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use MelisEngine\Model\MelisPage;

class MelisPageService implements MelisPageServiceInterface, ServiceLocatorAwareInterface
{
	protected $serviceLocator;
	
	public function setServiceLocator(ServiceLocatorInterface $sl)
	{
		$this->serviceLocator = $sl;
		return $this;
	}
	
	public function getServiceLocator()
	{
		return $this->serviceLocator;
	}	
	
	public function getDatasPage($idPage, $type = 'published')
	{
	    if (empty($idPage))
	        return null;
	    
		$melisPage = new MelisPage();
		$melisPage->setId($idPage);
		$melisPage->setType($type);
		
		$tablePageTree = $this->serviceLocator->get('MelisEngineTablePageTree');
		$melisPageTreePublished = $tablePageTree->getFullDatasPage($idPage, 'published');
		$datasPagePublished = $melisPageTreePublished->current();
		
		$tplId = 0;
		if ($type == 'published')
		{
			$melisPage->setMelisPageTree($datasPagePublished);
			if ($melisPageTreePublished->count() > 0)
				if (!empty($datasPagePublished->page_tpl_id))
					$tplId = $datasPagePublished->page_tpl_id;
		}
		else
		{
			$tablePageTreeSaved = $this->serviceLocator->get('MelisEngineTablePageTree');
			$melisPageTreeSaved = $tablePageTreeSaved->getFullDatasPage($idPage, 'saved');
			$datasPageSaved = $melisPageTreeSaved->current();
			
			if ($melisPageTreeSaved->count() > 0 && !empty($datasPageSaved->page_id))
			{
				$melisPage->setMelisPageTree($datasPageSaved);
				if (!empty($datasPageSaved->page_tpl_id))
					$tplId = $datasPageSaved->page_tpl_id;
			}
			else
			{
				$melisPage->setMelisPageTree($datasPagePublished);
				if ($melisPageTreePublished->count() > 0)
					if (!empty($datasPagePublished->page_tpl_id))
						$tplId = $datasPagePublished->page_tpl_id;
			}
		}

		if (!empty($tplId))
		{
			$tableTemplate = $this->serviceLocator->get('MelisEngineTableTemplate');
			$melisTemplateRes = $tableTemplate->getEntryById($tplId);
			$melisPage->setMelisTemplate($melisTemplateRes->current());
		}
		
		return $melisPage;
	}
}