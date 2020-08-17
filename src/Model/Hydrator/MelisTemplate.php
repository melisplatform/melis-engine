<?php 

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Hydrator;

class MelisTemplate
{
    protected $unfilteredDataCount = 0;
    protected $filteredDataCount = 0;

    public function getArrayCopy()
	{
		return get_object_vars($this);
	}

    public function getUnfilteredDataCount()
    {
        return $this->unfilteredDataCount;
    }
    public function setUnfilteredDataCount(int $count = 0)
    {
        $this->unfilteredDataCount = $count;
    }
    public function getFilteredDataCount()
    {
        return $this->filteredDataCount;
    }
    public function setFilteredDataCount(int $count = 0)
    {
        $this->filteredDataCount = $count;
    }
}