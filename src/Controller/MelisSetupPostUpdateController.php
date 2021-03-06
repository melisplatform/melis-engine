<?php

/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2015 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Controller;

use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use MelisCore\MelisSetupInterface;
use MelisCore\Controller\MelisAbstractActionController;

/**
 * @property bool $showOnMarketplacePostSetup
 */
class MelisSetupPostUpdateController extends MelisAbstractActionController implements MelisSetupInterface
{
    /**
     * flag for Marketplace whether to display the setup form or not
     * @var bool $showOnMarketplacePostSetup
     */
    public $showOnMarketplacePostSetup = true;

    /**
     * @return \Laminas\View\Model\ViewModel
     */
    public function getFormAction()
    {
        $view = new ViewModel();
        $view->setTerminal(true);

        return $view;
    }

    /**
     * @return \Laminas\View\Model\JsonModel
     */
    public function validateFormAction()
    {
        return new JsonModel([]);
    }

    /**
     * @return \Laminas\View\Model\JsonModel
     */
    public function submitAction()
    {
        return new JsonModel([]);
    }

}
