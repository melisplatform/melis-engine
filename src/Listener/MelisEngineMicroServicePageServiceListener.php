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
use MelisCore\Listener\MelisCoreGeneralListener;

class MelisEngineMicroServicePageServiceListener extends MelisCoreGeneralListener implements ListenerAggregateInterface
{

    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $sharedEvents      = $events->getSharedManager();

        $callBackHandler = $sharedEvents->attach(
            'MelisCore',
            'melis_core_microservice_amend_data',
            function($e){

                $sm = $e->getTarget()->getEvent()->getApplication()->getServiceManager();
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

                    $scheme  = parse_url($uri, PHP_URL_SCHEME);
                    $domain  = $scheme.'://'.parse_url($uri, PHP_URL_HOST);


                    set_time_limit(0);
                    $content = file_get_contents($uri);
                    $content = str_replace('href="', 'href="'.$domain.'', $content);
                    $content = str_replace('src="', 'src="'.$domain.'', $content);

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