<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2017 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Form\Factory;

use Laminas\ServiceManager\ServiceManager;
use MelisCore\Form\Factory\MelisSelectFactory;
use Laminas\Stdlib\ArrayUtils;

/**
 * Tags select factory to fill the tag to use list
 */
class PluginMenuBasedOnTagsSelectFactory extends MelisSelectFactory
{
	protected function loadValueOptions(ServiceManager $serviceManager)
	{
		$config = $serviceManager->get('config');

		$request = $serviceManager->get('request');
		$parameters = $request->getQuery('parameters', array());
		$module = (!empty($parameters['module'])) ? $parameters['module'] : '';

        $valueoptions = $config['plugins']['melisfront']['plugins']['MelisFrontMenuBasedOnTagPlugin']['melis']['tags_list'];
		$newValueOptions = array();
		
		foreach ($valueoptions as $val)
		    $newValueOptions[$val] = $val;

		return $newValueOptions;
	}

}