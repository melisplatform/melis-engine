<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2015 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Controller;

use Laminas\Session\Container;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use MelisCore\MelisSetupInterface;
use MelisCore\Controller\MelisAbstractActionController;

/**
 * @property bool $showOnMarketplacePostSetup
 */
class MelisSetupPostDownloadController extends MelisAbstractActionController implements MelisSetupInterface
{
    /**
     * flag for Marketplace whether to display the setup form or not
     * @var bool $showOnMarketplacePostSetup
     */
    public $showOnMarketplacePostSetup = false;

    /**
     * @return \Laminas\View\Model\ViewModel
     */
    public function getFormAction()
    {
        $request = $this->getRequest();

        //startSetup button indicator
        $btnStatus = (bool) $request->getQuery()->get('btnStatus');

        $form = $this->getForm();
        $container = new Container('melis_modules_configuration_status');
        $formData = isset($container['formData']) ? (array) $container['formData'] : null;

        if ($formData) {
            $form->setData($formData);
        }

        $view = new ViewModel();
        $view->setTerminal(true);
        $view->setVariable('form', $form);
        $view->btnStatus = $btnStatus;

        return $view;
    }

    /**
     * @return \Laminas\View\Model\JsonModel
     */
    public function validateFormAction()
    {
        $success = false;
        $message = 'tr_install_setup_message_ok';
        $errors = [];

//        $data = $this->getTool()->sanitizeRecursive($this->params()->fromRoute('post'));
//
//        $form = $this->getForm();
//        $form->setData($data);
//
//        if ($form->isValid()) {
//            $success = true;
//            $message = 'tr_install_setup_message_ok';
//        } else {
//            $errors = $this->formatErrorMessage($form->getMessages());
//        }
        // no validation
        $success = true;
        $response = [
            'success' => $success,
            'message' => $this->getTool()->getTranslation($message),
            'errors' => $errors,
            'form' => 'melis_core_setup_user_form',
        ];

        return new JsonModel($response);
    }

    public function submitAction()
    {

        $success = 1;
        $message = 'tr_install_setup_message_ko';
        $title   = 'tr_install_setup_title';
        $errors  = array();
        $form = $this->getForm();

        //Services
        $tablePlatformIds = $this->getServiceManager()->get('MelisEngineTablePlatformIds');

        //  if($form->isValid()) {

        try {
            $container = new Container('melis_modules_configuration_status');
            $hasErrors = false;

//            foreach ($container->getArrayCopy() as $module) {
//                if (!$module)
//                    $hasErrors = true;
//            }

            $request = $this->getRequest();
            $uri     = $request->getUri();
            $scheme  = $uri->getScheme();
            $siteDomain = $uri->getHost();

            $pageIdStart = $form->get('pids_page_id_start')->getValue();
            $pageIdCurrent = $form->get('pids_page_id_current')->getValue();
            $pageIdEnd = $form->get('pids_page_id_end')->getValue();
            $tplIdStart = $form->get('pids_tpl_id_start')->getValue();
            $tplIdCurrent = $form->get('pids_tpl_id_current')->getValue();
            $tplIdEnd = $form->get('pids_tpl_id_end')->getValue();


            // Getting current Platform
            $environmentName = getenv('MELIS_PLATFORM');
            $tablePlatform = $this->getServiceManager()->get('MelisCoreTablePlatform');
            $platform = $tablePlatform->getEntryByField('plf_name', $environmentName)->current();

            //Save platformData
            if (false === $hasErrors) {
                $tablePlatformIds->save(array(

                    'pids_page_id_start' => $pageIdStart,
                    'pids_page_id_current' => $pageIdCurrent,
                    'pids_page_id_end' => $pageIdEnd,
                    'pids_tpl_id_start' => $tplIdStart,
                    'pids_tpl_id_current' => $tplIdCurrent,
                    'pids_tpl_id_end' => $tplIdEnd
                ));

                $tableSiteDomain = $this->getServiceManager()->get('MelisEngineTableSiteDomain');
                $tableSite = $this->getServiceManager()->get('MelisEngineTableSite');

                $container = new \Laminas\Session\Container('melisinstaller');
                $container = $container->getArrayCopy();
                $cmsSiteSrv = $this->getServiceManager()->get('MelisCmsSiteService');

                $selectedSite = isset($container['site_module']['site']) ? $container['site_module']['site'] : null;

                $environments = isset($container['environments']['new']) ? $container['environments']['new'] : null;
                $siteId = 1;

                if ($selectedSite) {
                    if ($selectedSite == 'NewSite') {

                        $dataSite = array(
                            # Site Module name
                            'site_name' => isset($container['site_module']['website_module']) ? $container['site_module']['website_module'] : null,
                            # Site Label
                            'site_label' => isset($container['site_module']['website_name']) ? $container['site_module']['website_name'] : null,
                        );

                        $dataDomain = array(
                            'sdom_env' => $environmentName,
                            'sdom_scheme' => $scheme,
                            'sdom_domain' => $siteDomain
                        );

                        $dataSiteLang = $container['site_module']['language'];

                        $genSiteModule = true;

                        $siteModule = getenv('MELIS_MODULE');

                        $saveSiteResult = $cmsSiteSrv->saveSite($dataSite, $dataDomain, $dataSiteLang, array(), $siteModule, $genSiteModule, true, true);

                        if ($saveSiteResult['success']) {
                            foreach($saveSiteResult['site_ids'] as $key => $id){
                                $siteId = $id;
                            }
                        }
                    }else{
                        //create domain only if the user install MelisDemoCms or MelisDemoCmsTwig or other sites
                        $sitesList = ['MelisDemoCms', 'MelisDemoCmsTwig'];
                        if(in_array($selectedSite, $sitesList))
                            $this->saveCmsSiteDomain($scheme, $siteDomain);
                    }
                }



                $success = 1;
                $message = 'tr_install_setup_message_ok';
                $container['module_configuration_status'] = (bool)$success;
            }
        }
        catch(\Exception $e) {
            $errors = $e->getMessage();
        }

        // }
//        else {
//            $errors = $this->formatErrorMessage($form->getMessages());
//        }


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
        $melisTool = $this->getServiceManager()->get('MelisCoreTool');
        $melisTool->setMelisToolKey('MelisCmsSlider', 'MelisCmsSlider_details');

        return $melisTool;

    }
    /**
     * Create a form from the configuration
     * @return \Laminas\Form\ElementInterface
     */
    private function getForm()
    {
        $coreConfig = $this->getServiceManager()->get('MelisCoreConfig');
        $form = $coreConfig->getItem('melis_engine_setup/forms/melis_installer_platform_data');

        $factory = new \Laminas\Form\Factory();
        $formElements = $this->getServiceManager()->get('FormElementManager');
        $factory->setFormElementManager($formElements);
        $form = $factory->createForm($form);

        return $form;

    }
    private function formatErrorMessage($errors = array())
    {
        $melisMelisCoreConfig = $this->getServiceManager()->get('MelisCoreConfig');
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

    private function saveCmsSiteDomain($scheme, $site)
    {
        $container = new \Laminas\Session\Container('melisinstaller');

        // default platform
        $environments       = $container['environments'];
        $defaultEnvironment = $environments['default_environment'];
        $siteCtr            = 1;

        if($defaultEnvironment) {

            $defaultPlatformData[$siteCtr-1] = array(
                'sdom_site_id' => $siteCtr,
                'sdom_env'     => getenv('MELIS_PLATFORM'),
                'sdom_scheme'  => $scheme,
                'sdom_domain'  => $site
            );

            $platforms     = isset($environments['new']) ? $environments['new'] : null;
            $platformsData = array();

            if($platforms) {
                foreach($platforms as $platform) {
                    $platformsData[] = array(
                        'sdom_site_id' => $siteCtr,
                        'sdom_env'     => $platform[0]['sdom_env'],
                        'sdom_scheme'  => $platform[0]['sdom_scheme'],
                        'sdom_domain'  => $platform[0]['sdom_domain']
                    );
                }
            }

            $platformsData = array_merge($defaultPlatformData, $platformsData);

            $siteDomainTable = $this->getServiceManager()->get('MelisEngineTableSiteDomain');

            foreach($platformsData as $data) {
                $siteDomainTable->save($data);
            }

        }

    }
}
