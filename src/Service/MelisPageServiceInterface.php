<?php
	
namespace MelisEngine\Service;

interface MelisPageServiceInterface 
{
	public function getDatasPage($idPage, $type = 'published');
}