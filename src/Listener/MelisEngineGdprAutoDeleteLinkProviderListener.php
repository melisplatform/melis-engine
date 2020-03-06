<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2015 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Listener;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use MelisCore\Listener\MelisCoreGeneralListener;

class MelisEngineGdprAutoDeleteLinkProviderListener extends MelisCoreGeneralListener implements ListenerAggregateInterface
{

    public function attach(EventManagerInterface $events)
    {
        $sharedEvents = $events->getSharedManager();

        $callBackHandler = $sharedEvents->attach(
            '*',
            'melis_engine_gdpr_auto_delete_link_provider',
            function($e){
                $params = $e->getParams();
                $pageId = isset($params['pageId']) ? $params['pageId'] : null;
                if (!is_null($pageId)) {
                    $serviceManager = $e->getTarget()->getServiceLocator();
                    $melisTree = $serviceManager->get('MelisEngineTree');

                    return $melisTree->getPageLink($pageId,true);
                }
            },
            1000);
        $this->listeners[] = $callBackHandler;
    }
}