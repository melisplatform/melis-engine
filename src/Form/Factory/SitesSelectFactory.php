<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2016 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Form\Factory;

use Laminas\ServiceManager\ServiceManager;
use MelisCore\Form\Factory\MelisSelectFactory;

/**
 * This class creates a select box for melis languages
 *
 */
class SitesSelectFactory extends MelisSelectFactory
{
    protected function loadValueOptions(ServiceManager $serviceManager)
    {
        $table = $serviceManager->get('MelisEngineTableSiteDomain');
        $sites = $table->getSitesByEnv();

        $valueoptions = [];
        foreach($sites as $site)
            $valueoptions[$site->sdom_site_id] = $site->sdom_domain;

        return $valueoptions;
    }
}