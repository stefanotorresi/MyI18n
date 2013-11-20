<?php
/**
 *
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n;

use Zend\Console\Console;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Feature;
use Zend\Stdlib\ArrayUtils;

class Module implements
    Feature\AutoloaderProviderInterface,
    Feature\ConfigProviderInterface,
    Feature\ServiceProviderInterface,
    Feature\FormElementProviderInterface
{
    public function onBootstrap(MvcEvent $e)
    {
        // nothing to do if in console environment
        if (Console::isConsole()) {
            return;
        }

        $app = $e->getApplication();
        $eventManager = $app->getEventManager();
        $serviceManager = $app->getServiceManager();

        /* @var $localeStrategy LocaleStrategy */
        $localeStrategy = $serviceManager->get('MyI18n\LocaleStrategy');

        $eventManager->attach($localeStrategy);
    }

    public function getDir()
    {
        return __DIR__ . '/../..';
    }

    /**
     * {@inheritdoc}
     */
    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\ClassMapAutoloader' => [
                $this->getDir() . '/autoload_classmap.php'
            ],
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__,
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        $config = include $this->getDir() . '/config/module.config.php';

        $configFiles = array(
            'router.config.php',
            'doctrine.config.php',
        );

        foreach ($configFiles as $configFile) {
            $configFilePath = $this->getDir() . '/config/' . $configFile;
            $config = ArrayUtils::merge($config, include $configFilePath);
        }

        return $config;
    }

    /**
     * {@inheritdoc}
     */
    public function getServiceConfig()
    {
        return include $this->getDir() . '/config/services.config.php';
    }

    /**
     * {@inheritdoc}
     */
    public function getFormElementConfig()
    {
        return include $this->getDir() . '/config/form-elements.config.php';
    }
}
