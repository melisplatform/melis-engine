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
		if ($siteModule !== '' && file_exists($siteconfig)) {
		    $config = ArrayUtils::merge($config, require $siteconfig);
		} elseif ($siteModule !== '' && $this->isVendorSite($serviceManager, $siteModule)) {
		    // Site installed with composer (vendor/, e.g. MelisDemoCms): it is not loaded on
		    // back-office requests, so its plugin templates are read from its own Module config,
		    // the same config the front uses (0011040)
		    $siteModuleClass = $siteModule . '\\Module';
		    $config = ArrayUtils::merge($config, (new $siteModuleClass())->getConfig());
		}

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

	/**
	 * True when $siteModule is a composer-installed Melis site exposing a Module config
	 */
	private function isVendorSite(ServiceManager $serviceManager, $siteModule)
	{
		$moduleClass = $siteModule . '\\Module';
		if (!class_exists($moduleClass) || !method_exists($moduleClass, 'getConfig'))
			return false;

		try {
			return $serviceManager->get('MelisEngineComposer')->isSiteModule($siteModule);
		} catch (\Throwable $e) {
			return false;
		}
	}

}