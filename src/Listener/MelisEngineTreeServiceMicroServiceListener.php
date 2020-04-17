<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2015 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Listener;

use Laminas\EventManager\EventManagerInterface;
use Laminas\EventManager\ListenerAggregateInterface;
use MelisCore\Listener\MelisGeneralListener;

class MelisEngineTreeServiceMicroServiceListener extends MelisGeneralListener implements ListenerAggregateInterface
{
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->attachEventListener(
            $events,
            '*',
            'melis_core_microservice_amend_data',
            function($e){

                $sm = $e->getTarget()->getEvent()->getApplication()->getServiceManager();
                $params = $e->getParams();

                $module  = isset($params['module'])  ? $params['module']  : null;
                $service = isset($params['service']) ? $params['service'] : null;
                $method  = isset($params['method'])  ? $params['method']  : null;
                $post    = isset($params['post'])  ? $params['post']  : null;
                $results = isset($params['results'])  ? $params['results']  : null;

                if($module == 'MelisEngine' && $service == 'MelisTreeService' && $method == 'getPageChildren') {

                   $results = $results;

                }
                else if($module == 'MelisEngine' && $service == 'MelisTreeService' && $method == 'getPageFather') {
                    $results = $results;
                }
                else if($module == 'MelisEngine' && $service == 'MelisTreeService' && $method == 'getDomainByPageId') {

                }

                return [
                    'module'  => $module,
                    'service' => $service,
                    'method'  => $method,
                    'post'    => $post,
                    'results' => $results
                ];
            },
            1000
        );
    }
}