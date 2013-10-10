<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n;

use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Feature;

class Module implements
    Feature\AutoloaderProviderInterface,
    Feature\ConfigProviderInterface,
    Feature\ServiceProviderInterface
{
    public function onBootstrap(MvcEvent $e)
    {
        $app = $e->getApplication();
        $events = $app->getEventManager();
        $services = $app->getServiceManager();

        /* @var $strategy LocaleStrategy */
        $strategy = $services->get('MyI18n\LocaleStrategy');

        $strategy->attach($events);
    }

    public function getDir()
    {
        return __DIR__ . '/../..';
    }

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

    public function getConfig()
    {
        return include $this->getDir() . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return include $this->getDir() . '/config/services.config.php';
    }
}
