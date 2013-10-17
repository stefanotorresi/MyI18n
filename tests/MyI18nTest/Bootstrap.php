<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18nTest;

use Composer\Autoload\ClassLoader;

class Bootstrap
{
    public static function init()
    {
        $vendorPath = static::findParentPath('vendor');

        $loader = require $vendorPath . '/autoload.php';

        if (! $loader instanceof ClassLoader) {
            throw new \RuntimeException("Autoloader could not be found. Did you run 'composer install --dev'?");
        }

        $loader->add('MyI18nTest', dirname(__DIR__));

        if (file_exists('./config/test.application.config.php')) {
            $config = require './config/test.application.config.php';
        } else {
            $config = require './config/test.application.config.php.dist';
        }

        \DoctrineORMModuleTest\Util\ServiceManagerFactory::setConfig($config);
    }

    /**
     * @param $path
     * @return bool|string
     */
    protected static function findParentPath($path)
    {
        $dir = __DIR__;
        $previousDir = '.';
        while (!is_dir($dir . '/' . $path)) {
            $dir = dirname($dir);
            if ($previousDir === $dir) return false;
            $previousDir = $dir;
        }

        return $dir . '/' . $path;
    }
}
