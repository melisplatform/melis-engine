<?php
/**
 * Melis Technology (http://www.melistechnology.com)
 *
 * @copyright Copyright (c) 2015 Melis Technology (http://www.melistechnology.com)
 *
 */

namespace MelisEngine\Service;

use Composer\Composer;
use Composer\Factory;
use Composer\IO\NullIO;
use MelisCore\Service\MelisServiceManager;

class MelisEngineComposerService extends MelisServiceManager
{
    /**
     * @var Composer
     */
    protected $composer;

    /**
     * @return \Composer\Composer
     */
    public function getComposer()
    {
        if (is_null($this->composer)) {
            $composer = new \MelisComposerDeploy\MelisComposer();
            $this->composer = $composer->getComposer();
        }

        return $this->composer;
    }

    /**
     * @param Composer $composer
     *
     * @return $this
     */
    public function setComposer(Composer $composer)
    {
        $this->composer = $composer;

        return $this;
    }

    /**
     * Returns all melisplatform-module packages loaded by composer
     * @return array
     */
    public function getVendorModules()
    {
        //try to get modules from cache
        $cacheKey = 'getVendorModulesEngine';
        $cacheConfig = 'meliscms_page';
        $melisEngineCacheSystem = $this->getServiceManager()->get('MelisEngineCacheSystem');
        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
        if(!is_null($results)) return $results;

        $melisComposer = new \MelisComposerDeploy\MelisComposer();
        $melisInstalledPackages = $melisComposer->getInstalledPackages();

        $packages = array_filter($melisInstalledPackages, function ($package) {

            $type = $package->type;
            $extra = $package->extra ?? [];
            if(!empty($extra)) {
                $isMelisModule = true;
                if (property_exists($extra,'melis-module')) {
                    $key = 'melis-module';
                    if (!$extra->$key)
                        $isMelisModule = false;
                }

                /** @var CompletePackage $package */
                return $type === 'melisplatform-module' &&
                    property_exists($extra,'module-name') && $isMelisModule;
            }
        });

        $modules = array_map(function ($package) {
            $extra = (array) $package->extra;
            /** @var CompletePackage $package */
            return $extra['module-name'];
        }, $packages);

        sort($modules);

        $melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $modules);

        return $modules;
    }

    /**
     * @param $moduleName
     * @param bool $returnFullPath
     * @return string
     */
    public function getComposerModulePath($moduleName, $returnFullPath = true)
    {
        //try to get module path from cache
        $cacheKey = 'getComposerModulePathEngine_'.$moduleName.'_'.$returnFullPath;
        $cacheConfig = 'meliscms_page';
        $melisEngineCacheSystem = $this->getServiceManager()->get('MelisEngineCacheSystem');
        $results = $melisEngineCacheSystem->getCacheByKey($cacheKey, $cacheConfig);
        if(!is_null($results)) return $results;

        $melisComposer = new \MelisComposerDeploy\MelisComposer();
        $path = $melisComposer->getComposerModulePath($moduleName, $returnFullPath);

        //save cache
        $melisEngineCacheSystem->setCacheByKey($cacheKey, $cacheConfig, $path);

        return $path;
    }

    /**
     * @param $module
     *
     * @return bool
     */
    public function isSiteModule($module)
    {
        $melisComposer = new \MelisComposerDeploy\MelisComposer();
        $packages = $melisComposer->getInstalledPackages();

        $repo = null;

        foreach ($packages as $package) {
            $packageModuleName = isset($package->extra) ? (array) $package->extra : null;

            if (isset($packageModuleName['module-name']) && $packageModuleName['module-name'] == $module) {
                $repo = (array) $package->extra;
                break;
            }
        }

        if (isset($repo['melis-site'])) {
            return (bool) $repo['melis-site'] ?? false;
        }

        return false;
    }
}
