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

class MelisSetupController extends AbstractActionController
{
    public function setupFormAction()
    {
        $request = $this->getRequest();

        //startSetup button indicator
        $btnStatus = (bool) $request->getQuery()->get('btnStatus');

        $view = new ViewModel();
        $view->form = $this->getForm();
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

        $data = $this->getTool()->sanitizeRecursive($this->params()->fromRoute());

        $form = $this->getForm();
        $form->setData($data);

        //Services
        $tablePlatformIds = $this->getServiceLocator()->get('MelisEngineTablePlatformIds');

        if($form->isValid()) {

            $container = new \Zend\Session\Container('melis_modules_configuration_status');
            $hasErrors = false;

            foreach($container->getArrayCopy() as $module) {
                if(!$module)
                    $hasErrors = true;
            }



            $scheme        = $form->get('sdom_scheme')->getValue();
            $siteDomain    = $form->get('sdom_domain')->getValue();
            $pageIdStart   = $form->get('pids_page_id_start')->getValue();
            $pageIdCurrent = $form->get('pids_page_id_current')->getValue();
            $pageIdEnd     = $form->get('pids_page_id_end')->getValue();
            $tplIdStart    = $form->get('pids_tpl_id_start')->getValue();
            $tplIdCurrent  = $form->get('pids_tpl_id_current')->getValue();
            $tplIdEnd      = $form->get('pids_tpl_id_end')->getValue();


            // Getting current Platform
            $environmentName = getenv('MELIS_PLATFORM');
            $tablePlatform   = $this->getServiceLocator()->get('MelisCoreTablePlatform');
            $platform        = $tablePlatform->getEntryByField('plf_name', $environmentName)->current();

            //Save platformData
            if(false === $hasErrors) {
                $tablePlatformIds->save(array(

                    'pids_page_id_start'    => $pageIdStart,
                    'pids_page_id_current'  => $pageIdCurrent,
                    'pids_page_id_end'      => $pageIdEnd,
                    'pids_tpl_id_start'     => $tplIdStart,
                    'pids_tpl_id_current'   => $tplIdCurrent,
                    'pids_tpl_id_end'       => $tplIdEnd
                ));

                $tableSiteDomain = $this->getServiceLocator()->get('MelisEngineTableSiteDomain');
                $tableSite       = $this->getServiceLocator()->get('MelisEngineTableSite');

                $cmsSiteSrv = $this->getServiceLocator()->get('MelisCmsSiteService');

                $container = new \Zend\Session\Container('melisinstaller');
                $selectedSite = isset($container['site_module']['site']) ? $container['site_module']['site'] : null;

                $environments = isset($container['environments']['new']) ? $container['environments']['new'] : null;
                $siteId = 1;
                if($selectedSite)  {
                    if($selectedSite == 'NewSite') {

                        $dataSite = array(
                            'site_name' => $selectedSite['cms_data']['web_form']['website_name']
                        );

                        $dataDomain = array(
                            'sdom_env' => $environmentName,
                            'sdom_scheme' => $scheme,
                            'sdom_domain' => $siteDomain
                        );

                        $dataSiteLang = $selectedSite['cms_data']['web_lang'];

                        $genSiteModule = true;

                        $siteModule = getenv('MELIS_MODULE');

                        $saveSiteResult = $cmsSiteSrv->saveSite($dataSite, $dataDomain, array(), $dataSiteLang, null, $genSiteModule, $siteModule);

                        if ($saveSiteResult['success']){
                            $siteId = $saveSiteResult['site_id'];
                        }
                    }
                }

                if($environments) {
                    foreach($environments as $sitePlatform => $siteDomains) {
                        foreach($siteDomains as $siteDomain) {

                            unset($siteDomain['app_interface_conf']);

                            $siteDomain['sdom_site_id'] = $siteId;
                            $tableSiteDomain->save($siteDomain);
                        }
                    }
                }

                $success = 1;
                $message = 'tr_install_setup_message_ok';
                $container['module_configuration_status'] = (bool) $success;
            }

        }
        else {
            $errors = $this->formatErrorMessage($form->getMessages());
        }


        $response = array(
            'success' => $success,
            'message' => $this->getTool()->getTranslation($message),
            'errors'  => $errors,
            'form'    => 'melis_installer_platform_data'
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
    /**
     * Create a form from the configuration
     * @param $formConfig
     * @return \Zend\Form\ElementInterface
     */
    private function getForm()
    {
        $coreConfig = $this->getServiceLocator()->get('MelisCoreConfig');
        $form = $coreConfig->getItem('melis_engine_setup/forms/melis_installer_platform_data');

        $factory = new \Zend\Form\Factory();
        $formElements = $this->serviceLocator->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $form = $factory->createForm($form);

        return $form;

    }
    private function formatErrorMessage($errors = array())
    {
        $melisMelisCoreConfig = $this->serviceLocator->get('MelisCoreConfig');
        $appConfigForm = $melisMelisCoreConfig->getItem('melis_engine_setup/forms/melis_installer_platform_data');
        $appConfigForm = $appConfigForm['elements'];

        foreach ($errors as $keyError => $valueError)
        {
            foreach ($appConfigForm as $keyForm => $valueForm)
            {
                if ($valueForm['spec']['name'] == $keyError &&
                    !empty($valueForm['spec']['options']['label']))
                    $errors[$keyError]['label'] = $valueForm['spec']['options']['label'];
            }
        }

        return $errors;
    }
}