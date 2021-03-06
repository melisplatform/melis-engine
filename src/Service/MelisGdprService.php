<?php
/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2019 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Service;


use MelisCore\Service\MelisGeneralService;

/**
 *
 * This service handles the user tabs system of Melis.
 *
 */
class MelisGdprService extends MelisGeneralService
{
    /**
     * Saves the banner content
     * @param int|null $bannerId
     * @param string $content
     * @param int|null $siteId
     * @param int|null $langId
     * @return mixed
     */
    public function saveBanner(
        int $bannerId = null,
        string $content = '',
        int $siteId = null,
        int $langId = null
    )
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = false;

        // Sending service start event
        $arrayParameters = $this->sendEvent('melis_gdpr_save_banner_start', $arrayParameters);

        // START BUSINESS LOGIC
        if (!empty($arrayParameters['content']) && !empty($arrayParameters['siteId']) && !empty($arrayParameters['langId'])) {
            // Transferring variables for readability
            $bannerId = $arrayParameters['bannerId'];
            $content = $arrayParameters['content'];
            $siteId = $arrayParameters['siteId'];
            $langId = $arrayParameters['langId'];

            try {
                $data = [
                    'mcgdpr_text_value' => $content,
                    'mcgdpr_text_lang_id' => $langId,
                    'mcgdpr_text_site_id' => $siteId,
                ];
                if (empty($bannerId)) {
                    $results = (int)$this->getMelisCmsGdprTextsTable()->save($data);
                } else {
                    $results = (int)$this->getMelisCmsGdprTextsTable()->save($data, $bannerId);
                }
            } catch (\Exception $exception) {
                echo $exception->getMessage();
            }
        };
        // END BUSINESS LOGIC

        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('melis_gdpr_save_banner__end', $arrayParameters);

        return $arrayParameters['results'];
    }

    public function deleteBannerById(int $bannerId = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = false;

        // Sending service start event
        $arrayParameters = $this->sendEvent('melis_gdpr_delete_banner_by_id_start', $arrayParameters);

        // START BUSINESS LOGIC
        if (!empty($arrayParameters['bannerId'])) {
            try {
                $bannerTable = $this->getMelisCmsGdprTextsTable();
                $results = $bannerTable->deleteById($arrayParameters['bannerId']);
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }
        // END BUSINESS LOGIC

        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('melis_gdpr_delete_banner_by_id_end', $arrayParameters);

        return $arrayParameters['results'];
    }

    /**
     * @param int|null $siteId
     * @param int|null $langId
     * @return mixed
     */
    public function getGdprBannerText(int $siteId = null, int $langId = null)
    {
        // Event parameters prepare
        $arrayParameters = $this->makeArrayFromParameters(__METHOD__, func_get_args());
        $results = [];

        // Sending service start event
        $arrayParameters = $this->sendEvent('melis_gdpr_get_banner_text_start', $arrayParameters);

        // START BUSINESS LOGIC
        // Transferring variables for readability
        $siteId = $arrayParameters['siteId'];
        $langId = $arrayParameters['langId'];

        /** @var \MelisEngine\Model\Tables\MelisCmsGdprTextsTable $bannerTable */
        $bannerTable = $this->getMelisCmsGdprTextsTable();
        try {
            $results = $bannerTable->getGdprBannerText($siteId, $langId);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        // END BUSINESS LOGIC

        // Adding results to parameters for events treatment if needed
        $arrayParameters['results'] = $results;
        // Sending service end event
        $arrayParameters = $this->sendEvent('melis_gdpr_get_banner_text_end', $arrayParameters);

        return $arrayParameters['results'];
    }

    /**
     * @return array | \MelisEngine\Model\Tables\MelisCmsGdprTextsTable
     */
    private function getMelisCmsGdprTextsTable()
    {
        return $this->getServiceManager()->get('MelisEngine\Model\Tables\MelisCmsGdprTextsTable');
    }
}
