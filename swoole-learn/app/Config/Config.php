<?php
/**
 * Project: hiphp
 * File: config.php
 *
 * Created by PhpStorm.
 * User: yangxionghai
 * Email: xionghaiyang@hk01.com
 * Date: 5/6/19
 * Time: 10:51 AM
 */

namespace App\Config;

use Illuminate\Config\Repository;
use Symfony\Component\Finder\Finder;

class Config extends Repository
{
    public static $instance;
    protected $configPath;

    public function __construct(array $items = [])
    {
        parent::__construct($items);
        $this->loadConfigurationFiles(ROOT_PATH . '/config/');
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    protected function loadConfigurationFiles($path)
    {
        $this->configPath = $path;

        foreach ($this->getConfigurationFiles() as $fileKey => $path) {
            $this->set($fileKey, require $path);
        }

        $configPaths = $this->getConfigurationFiles();
        if (is_array($configPaths)) {
            foreach ($configPaths as $fileKey => $path) {
                $envConfig = require $path;
                if (is_array($envConfig)) {
                    foreach ($envConfig as $envKey => $value) {
                        $this->set($fileKey . '.' . $envKey, $value);
                    }
                }
            }
        }
    }

    protected function getConfigurationFiles($environment = null)
    {
        $path = $environment ? $this->configPath . '/' .$environment : $this->configPath;
        if (!is_dir($path)) {
            return [];
        }

        $files = [];
        foreach (Finder::create()->files()->name('*.php')->in($path)->depth(0) as $file) {
            $files[basename($file->getRealPath(), '.php')] = $file->getRealPath();
        }

        return $files;
    }
}
