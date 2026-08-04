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
 * Template select factory to fill the template list
 */
class PluginTemplateSelectFactory extends MelisSelectFactory
{
	protected function loadValueOptions(ServiceManager $serviceManager)
	{
		$config = $serviceManager->get('config');

		$request = $serviceManager->get('request');
		$parameters = $request->getQuery('parameters', array());
		$module = (!empty($parameters['module'])) ? $parameters['module'] : '';
		$pluginName = (!empty($parameters['pluginName'])) ? $parameters['pluginName'] : '';
		$siteModule = (!empty($parameters['siteModule'])) ? $parameters['siteModule'] : '';
		// Sécurité : $siteModule (paramètre de requête) construit un chemin passé à require() →
		// n'autoriser qu'un identifiant de module simple, sinon inclusion de fichier arbitraire
		// (LFI/RCE via « ../ » pointant vers un .php quelconque écrivable).
		if (!preg_match('/^[A-Za-z0-9_]+$/', (string) $siteModule)) {
		    $siteModule = '';
		}

		$siteconfig = $_SERVER['DOCUMENT_ROOT'] . "/../module/MelisSites/$siteModule/config/$siteModule.config.php";
		if ($siteModule !== '' && file_exists($siteconfig))
		    $config = ArrayUtils::merge($config, require $siteconfig);
		
		if (empty($config['plugins'][$module]['plugins'][$pluginName]))
            $valueoptions = array();
		else
		    $valueoptions = $config['plugins'][$module]['plugins'][$pluginName]['front']['template_path'];

		$translator = $serviceManager->get('translator');
		
		$newValueOptions = array();
		
		foreach ($valueoptions as $val)
		    $newValueOptions[$val] = $val;

		return $newValueOptions;
	}

}