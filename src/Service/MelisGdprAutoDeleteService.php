<?php
/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2019 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Service;


use MelisCore\Service\MelisCoreGdprAutoDeleteService;
use MelisEngine\Service\MelisEngineGeneralService;

/**
 *
 * This service handles the user tabs system of Melis.
 *
 */
class MelisGdprAutoDeleteService extends MelisEngineGeneralService
{
    /**
     * get the class to be used in verifying the user
     * @param $module
     * @return string
     */
    public function getServiceClassByModule($module)
    {
        $modules = $this->getDataOfAnEvent('melis_core_gdpr_auto_delete_modules_list',MelisCoreGdprAutoDeleteService::MODULE_LIST_KEY);
        if (!empty($modules)) {
            foreach ($modules as $moduleSet => $moduleOpts) {
                if ($moduleSet == $module) {
                    if (isset($moduleOpts['service']) && !empty($moduleOpts['service'])) {
                        return $moduleOpts['service'];
                    } else {
                        echo  "Missing service in module ". $module . " ";
                    }
                }
            }
        }
    }
    /**
     * get the list of warning users in every modules that was sent through their respective listeners
     * @return array
     */
    public function getAllModulesWarningListOfUsers()
    {
        return $this->getDataOfAnEvent(MelisCoreGdprAutoDeleteService::WARNING_EVENT, MelisCoreGdprAutoDeleteService::WARNING_LIST_KEY);
    }

    /**
     * get the list of second warning users in every modules that was sent through their respective listeners
     * @return array
     */
    public function getAllModulesSecondWarningListOfUsers()
    {
        return $this->getDataOfAnEvent(MelisCoreGdprAutoDeleteService::SECOND_WARNING_EVENT, MelisCoreGdprAutoDeleteService::SECOND_WARNING_LIST_KEY);
    }

    /**
     * trigger an event and then get data based from main key to retrieve or with sub key to retrieve
     * @param $mvcEventName
     * @param $mainKeyToRetrieve
     * @param null $subKeyToRetrieve
     * @return array
     */
    private function getDataOfAnEvent($mvcEventName, $mainKeyToRetrieve, $subKeyToRetrieve = null)
    {
        // trigger zend mvc event
        $list = $this->getEventManager()->trigger($mvcEventName,$this);

        $data = [];
        // get the returned data from each module listener
        for ($list->rewind(); $list->valid(); $list->next()) {
            // check if current data is not empty
            if (!empty($list->current())) {
                // get the lists
                foreach ($list->current()[$mainKeyToRetrieve] as $moduleName => $moduleOptions) {
                    if (!is_null($subKeyToRetrieve)) {
                        $data[$moduleName] = $moduleOptions[$subKeyToRetrieve] ?? [];
                    } else {
                        $data[$moduleName] = $moduleOptions;
                    }
                }
            }
        };

        return $data;
    }

    /**
     * @param $module
     * @param null $email
     * @return mixed|null
     */
    public function getWarningListOfUserByModule($module, $email = null)
    {
        $warningUsers = $this->getAllModulesWarningListOfUsers();
        $data = null;
        if (!empty($warningUsers)) {
            foreach ($warningUsers as $moduleName => $emails) {
                if ($moduleName == $module) {
                    // return module's emails
                    $data = $emails;
                    // if email params is present
                    if (!is_null($email)) {
                        foreach ($emails as $warningEmail => $emailOptions) {
                            // if found email
                            if ($warningEmail == $email) {
                                // return email
                                $data = $emailOptions;
                            }
                        }
                    }
                }
            }
        }

        return $data;
    }

    /**
     * calculate the diffrence of two dates in days
     * @param $date1
     * @param $date2
     * @return float
     */
    public function getDaysDiff($date1, $date2)
    {
        // get config time format 
        $timeFormat = $this->getServiceLocator()->get('MelisConfig')->getItem('melisfront/datas')['gdpr_auto_anonymized_time_format'] ?? null;
        $diff = 0;
        // 
        switch ($timeFormat) {
            case "d":
                $diff = round((strtotime($date2) - strtotime($date1)) / (60 * 60 * 24));
                break;
            case "m":
                $diff = round((time() - strtotime($date1)) / 60);
                break;
            default:
                $diff = round((strtotime($date2) - strtotime($date1)) / (60 * 60 * 24));
        }

        return $diff; 
    }

    /**
     * get auto delete configuration by siteId and module
     *
     * @param $siteId
     * @param $module
     */
    public function getAutoDeleteConfig($siteId, $module)
    {
        $configTable = $this->getServiceLocator()->get('MelisGdprAutodeleteConfigTable');

        return $configTable->getAutoDeleteConfig($siteId, $module)->current();
    }

    /**
     * remove an account on the delete email set table
     *
     * @param $accountId
     * @param $module
     * @param $siteId
     */
    public function removeDeleteEmailSentLog($accountId, $module, $siteId)
    { 
        $status = false;
        $emailSentTbl = $this->getServiceLocator()->get('MelisGdprDeleteEmailsSentTable');
        // delete by field
        $status = $emailSentTbl->deleteEmailSentData($accountId, $module, $siteId);

        return $status;

    }
}
