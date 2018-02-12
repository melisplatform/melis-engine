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
use Zend\Session\Container;
class MelisSetupController extends AbstractActionController
{
//    public function setupFormAction()
//    {
//        $request = $this->getRequest();
//
//        //startSetup button indicator
//        $btnStatus = (bool) $request->getQuery()->get('btnStatus');
//
//		$form 		= $this->getForm();
//		$container  = new Container('melis_modules_configuration_status');
//		$formData 	= isset($container['formData']) ? (array) $container['formData'] : null;
//
//        if($formData)
//            $form->setData($formData);
//
//        $view = new ViewModel();
//        $view->setVariable('form' , $form);
//        $view->setTerminal(true);
//        $view->btnStatus = $btnStatus;
//        return $view;
//
//    }
	
//	public function setupValidateDataAction()
//	{
//		$success = 1;
//        $message = 'tr_install_setup_message_ko';
//        $errors  = array();
//
//		$data = $this->getTool()->sanitizeRecursive($this->params()->fromRoute());
//
//		$form = $this->getForm();
//        $form->setData($data);
//
//		if($form->isValid()) {
//			$success = 1;
//			$message = 'tr_install_setup_message_ok';
//		}
//		else {
//			$errors = $this->formatErrorMessage($form->getMessages());
//		}
//
//
//        $response = array(
//            'success' => $success,
//            'message' => $this->getTool()->getTranslation($message),
//            'errors'  => $errors,
//            'form'    => 'melis_installer_platform_data'
//        );
//
//        return new JsonModel($response);
//	}

    public function setupResultAction()
    {
        $success = 1;
        $message = 'tr_install_setup_message_ko';
        $title   = 'tr_install_setup_title';
        $errors  = array();

        $data = $this->getTool()->sanitizeRecursive($this->params()->fromRoute());

        $form = $this->getForm();
        $form->setData($data);

        //Services
        $tablePlatformIds = $this->getServiceLocator()->get('MelisEngineTablePlatformIds');

      //  if($form->isValid()) {

            try {
                $container = new Container('melis_modules_configuration_status');
                $hasErrors = false;

                foreach ($container->getArrayCopy() as $module) {
                    if (!$module)
                        $hasErrors = true;
                }
				
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
                $tablePlatform = $this->getServiceLocator()->get('MelisCoreTablePlatform');
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

                    $tableSiteDomain = $this->getServiceLocator()->get('MelisEngineTableSiteDomain');
                    $tableSite = $this->getServiceLocator()->get('MelisEngineTableSite');

                  //  $cmsSiteSrv = $this->getServiceLocator()->get('MelisCmsSiteService');
//
//                    $container = new \Zend\Session\Container('melisinstaller');
//                    $selectedSite = isset($container['site_module']['site']) ? $container['site_module']['site'] : null;
//
//                    $environments = isset($container['environments']['new']) ? $container['environments']['new'] : null;
//                    $siteId = 1;
//
//                    if ($selectedSite) {
//                        if ($selectedSite == 'NewSite') {
//
//                            $dataSite = array(
//                                'site_name' => $selectedSite['cms_data']['web_form']['website_name']
//                            );
//
//                            $dataDomain = array(
//                                'sdom_env' => $environmentName,
//                                'sdom_scheme' => $scheme,
//                                'sdom_domain' => $siteDomain
//                            );
//
//                            $dataSiteLang = $selectedSite['cms_data']['web_lang'];
//
//                            $genSiteModule = true;
//
//                            $siteModule = getenv('MELIS_MODULE');
//
//                           // $saveSiteResult = $cmsSiteSrv->saveSite($dataSite, $dataDomain, array(), $dataSiteLang, null, $genSiteModule, $siteModule);
//
//                            if ($saveSiteResult['success']) {
//                                $siteId = $saveSiteResult['site_id'];
//                            }
//                        }
//                    }

                  //  $this->saveCmsSiteDomain($scheme, $siteDomain);


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
        $melisTool = $this->getServiceLocator()->get('MelisCoreTool');
        $melisTool->setMelisToolKey('MelisCmsSlider', 'MelisCmsSlider_details');

        return $melisTool;

    }
    /**
     * Create a form from the configuration
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

    private function saveCmsSiteDomain($scheme, $site)
    {
        $container = new \Zend\Session\Container('melisinstaller');

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

            $siteDomainTable = $this->getServiceLocator()->get('MelisEngineTableSiteDomain');

            foreach($platformsData as $data) {
                $siteDomainTable->save($data);
            }

        }

    }
}
