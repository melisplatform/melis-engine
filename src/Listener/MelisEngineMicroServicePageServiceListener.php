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

class MelisEngineMicroServicePageServiceListener extends MelisCoreGeneralListener implements ListenerAggregateInterface
{

    public function attach(EventManagerInterface $events)
    {
        $sharedEvents      = $events->getSharedManager();

        $callBackHandler = $sharedEvents->attach(
            'MelisCore',
            'melis_core_microservice_amend_data',
            function($e){

                $sm = $e->getTarget()->getServiceLocator();
                $params = $e->getParams();


                $module  = isset($params['module'])  ? $params['module']  : null;
                $service = isset($params['service']) ? $params['service'] : null;
                $method  = isset($params['method'])  ? $params['method']  : null;
                $post    = isset($params['post'])    ? $params['post']    : null;
                $results = isset($params['results']) ? $params['results']    : null;

                if($module == 'MelisEngine' &&
                   $service == 'MelisPageService' &&
                   $method  == 'getDatasPage') {

                    $pageId = (int) $post['idPage'];

                    $treeSvc = $sm->get('MelisEngineTree');
                    $uri     = $treeSvc->getPageLink($pageId, true);
                    $uri     = substr($uri, 0, strlen($uri)-1);

                    set_time_limit(0);
                    $content = file_get_contents($uri);
                    $content = str_replace('href="', 'href="'.$uri.'', $content);
                    $content = str_replace('src="', 'src="'.$uri.'', $content);

                    $pageTree = $results->getMelisPageTree();
                    $pageTree->page_content = $content;
                    $pageTree->page_uri = $uri;

                    $results->getMelisPageTree = $pageTree;

                }

                return array(
                    'module'  => $module,
                    'service' => $service,
                    'method'  => $method,
                    'post'    => $post,
                    'results' => $results
                );

            },
            -10000);

        $this->listeners[] = $callBackHandler;
    }
}