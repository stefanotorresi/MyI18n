<?php

/**
 *
 * @author Stefano Torresi <webdeveloper@stefanotorresi.it>
 */

namespace MyI18n;

use Zend\Mvc\MvcEvent;
use Zend\Session\Container as Session;

class Module
{
    const VERSION = '0.1.2';

    public function onBootstrap(MvcEvent $e)
    {
        $app = $e->getApplication();
        $events = $app->getEventManager();
        $services = $app->getServiceManager();

        /* @var $strategy LocaleStrategy */
        $strategy = $services->get("MyI18n\\LocaleStrategy");

        $strategy->attach($events);
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/../../autoload_classmap.php'
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__  => __DIR__
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'MyI18n\LocaleStrategy' => function ($services) {
                    $config = $services->get('config');
                    $instance = new LocaleStrategy($config[__NAMESPACE__]);

                    return $instance;
                },
            ),
            'initializers' => array(
                function ($instance, $services) {
                    if ($instance instanceof Detector\AbstractDetector) {
                        $config = $services->get('MyI18n\LocaleStrategy')->getConfig();
                        $instance->setConfig($config);
                    }
                }
            ),
            'services' => array(
                'MyI18n\Session' => new Session(__NAMESPACE__),
            ),
        );
    }
}
