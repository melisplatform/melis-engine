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
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MelisEngineComposerService implements ServiceLocatorAwareInterface
{
    /** @var \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator */
    public $serviceLocator;

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
            // required by composer factory but not used to parse local repositories
            if (!isset($_ENV['COMPOSER_HOME'])) {
                putenv("COMPOSER_HOME=/tmp");
            }
            $factory = new Factory();
            $this->setComposer($factory->createComposer(new NullIO()));
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
        $repos = $this->getComposer()->getRepositoryManager()->getLocalRepository();

        $packages = array_filter($repos->getPackages(), function ($package) {
            /** @var CompletePackage $package */
            return $package->getType() === 'melisplatform-module' &&
                array_key_exists('module-name', $package->getExtra());
        });

        $modules = array_map(function ($package) {
            /** @var CompletePackage $package */
            return $package->getExtra()['module-name'];
        }, $packages);

        sort($modules);

        return $modules;
    }

    /**
     * @param $moduleName
     * @param bool $returnFullPath
     * @return string
     */
    public function getComposerModulePath($moduleName, $returnFullPath = true)
    {
        $repos = $this->getComposer()->getRepositoryManager()->getLocalRepository();
        $packages = $repos->getPackages();

        if (!empty($packages)) {
            foreach ($packages as $repo) {
                if ($repo->getType() == 'melisplatform-module') {
                    if (array_key_exists('module-name', $repo->getExtra())
                        && $moduleName == $repo->getExtra()['module-name']) {
                        foreach ($repo->getRequires() as $require) {
                            $source = $require->getSource();

                            if ($returnFullPath) {
                                return $_SERVER['DOCUMENT_ROOT'] . '/../vendor/' . $source;
                            } else {
                                return '/vendor/' . $source;
                            }
                        }
                    }
                }
            }
        }

        return '';
    }

    /**
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $sl
     *
     * @return $this
     */
    public function setServiceLocator(ServiceLocatorInterface $sl)
    {
        $this->serviceLocator = $sl;

        return $this;
    }

    /**
     * @param $module
     *
     * @return bool
     */
    public function isSiteModule($module)
    {
        $composerFile = $_SERVER['DOCUMENT_ROOT'] . '/../vendor/composer/installed.json';
        $composer = (array) \Zend\Json\Json::decode(file_get_contents($composerFile));

        $repo = null;

        foreach ($composer as $package) {
            $packageModuleName = isset($package->extra) ? (array) $package->extra : null;

            if (isset($packageModuleName['module-name']) && $packageModuleName['module-name'] == $module) {
                $repo = (array) $package->extra;
                break;
            }
        }

        if ($repo) {
            if(isset($repo['melis-site'])) {
                return (bool)$repo['melis-site'] ?? false;
            }
        }

        return false;
    }
}
