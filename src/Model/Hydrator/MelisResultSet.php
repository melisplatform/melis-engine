<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2019 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Model\Hydrator;

#[\AllowDynamicProperties]
class MelisResultSet
{
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}