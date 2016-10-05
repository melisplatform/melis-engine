<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2015 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model;

class MelisPageTree
{
	
	public function __construct()
	{
	}
	
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}
}