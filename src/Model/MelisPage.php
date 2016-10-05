<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2015 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model;


class MelisPage
{
	protected $id;
	protected $type;
	protected $melisPageTree;
	protected $melisTemplate;
	
	public function getId()
	{
		return $this->id;
	}
	
	public function setId($id)
	{
		$this->id = $id;
	}  
	
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}
	
	public function getType()
	{
		return $this->type;
	}
	
	public function setType($type)
	{
		$this->type = $type;
	}
	
	public function getMelisPageTree()
	{
		return $this->melisPageTree;
	}
	
	public function setMelisPageTree($melisPageTree)
	{
		$this->melisPageTree = $melisPageTree;
	}
	
	public function getMelisTemplate()
	{
		return $this->melisTemplate;
	}
	
	public function setMelisTemplate($melisTemplate)
	{
		$this->melisTemplate = $melisTemplate;
	}
	
	
}