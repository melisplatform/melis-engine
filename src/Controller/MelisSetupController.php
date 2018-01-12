<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2015 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Zend\Validator\File\Size;
use Zend\Validator\File\IsImage;
use Zend\Validator\File\Upload;
use Zend\File\Transfer\Adapter\Http;
use Zend\Session\Container;

class MelisSetupController extends AbstractActionController
{
    public function setupFormAction()
    {

        $coreConfig = $this->getServiceLocator()->get('MelisCoreConfig');
        $form = $coreConfig->getItem('melis_engine_setup/forms/melis_installer_platform_id');

        $request = $this->getRequest();

        //startSetup button indicator
        $btnStatus = (bool) $request->getQuery()->get('btnStatus');

        $view = new ViewModel();
        $view->form = $form;
        $view->setTerminal(true);
        $view->btnStatus = $btnStatus;
        return $view;

    }

    public function setupResultAction()
    {
        $success = 0;
        $message = 'tr_install_setup_message_ko';
        $title   = 'tr_install_setup_title';
        $errors  = array();

        $request = $this->getRequest();

        if($request->isPost()){

            //Services
            $tablePlatformIds = $this->getServiceLocator()->get('MelisEngineTablePlatformIds');

            //FormData
            $platformData = $request->getPost();

            if(!empty($platformData)){

                $pageIdStart   = $platformData->get('pids_page_id_start');
                $pageIdCurrent = $platformData->get('pids_page_id_current');
                $pageIdEnd     = $platformData->get('pids_page_id_end');
                $tplIdStart    = $platformData->get('pids_tpl_id_start');
                $tplIdCurrent  = $platformData->get('pids_tpl_id_current');
                $tplIdEnd      = $platformData->get('pids_tpl_id_end');


                // Getting current Platform
                $environmentName = getenv('MELIS_PLATFORM');
                $tablePlatform   = $this->getServiceLocator()->get('MelisCoreTablePlatform');
                $platform        = $tablePlatform->getEntryByField('plf_name', $environmentName)->current();

                //Save platformData
                $tablePlatformIds->save(array(

                    'pids_page_id_start'    => $pageIdStart,
                    'pids_page_id_current'  => $pageIdCurrent,
                    'pids_page_id_end'      => $pageIdEnd,
                    'pids_tpl_id_start'     => $tplIdStart,
                    'pids_tpl_id_current'   => $tplIdCurrent,
                    'pids_tpl_id_end'       => $tplIdEnd
                ));
                $success = 1;
                $message = 'tr_install_setup_message_ok';
            }

        }

        $response = array(
            'success' => $success,
            'message' => $this->getTool()->getTranslation($message),
            'errors'  => $errors
        );

        return new JsonModel($response);
    }

    /**
     * Returns the Tool Service Class
     * @return MelisCoreTool
     */
    private function getTool()
    {
        $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
        $melisTool->setMelisToolKey('MelisCmsSlider', 'MelisCmsSlider_details');

        return $melisTool;

    }
}