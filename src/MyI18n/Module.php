<?php
/**
 *
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n;

use MyBase\AbstractModule;
use Zend\Console\Console;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Feature;

class Module extends AbstractModule implements
    Feature\ServiceProviderInterface
{
    public function getConfigGlob()
    {
        return '{module,router,doctrine}.config.php';
    }

    /**
     * {@inheritdoc}
     */
    public function getServiceConfig()
    {
        return include $this->getDir() . '/config/services.config.php';
    }

    /**
     * @param MvcEvent $e
     */
    public function onBootstrap(MvcEvent $e)
    {
        // nothing to do if in console environment
        if (Console::isConsole()) {
            return;
        }

        $app = $e->getApplication();
        $eventManager = $app->getEventManager();
        $serviceManager = $app->getServiceManager();

        /* @var $localeStrategy Listener\LocaleAggregateListener */
        $localeStrategy = $serviceManager->get('MyI18n\Listener\LocaleAggregateListener');

        $eventManager->attach($localeStrategy);
    }
}
