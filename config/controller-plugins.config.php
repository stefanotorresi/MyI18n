<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

use MyI18n\Service;
use MyI18n\Controller\Plugin;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ServiceLocatorInterface;

return [
    'factories' => [
        'locale' =>
            function (ServiceLocatorInterface $serviceLocator) {
                $serviceManager = $serviceLocator instanceof AbstractPluginManager ?
                    $serviceLocator->getServiceLocator()
                    : $serviceLocator;

                /** @var Service\LocaleService $localeService */
                $localeService = $serviceManager->get('MyI18n\Service\LocaleService');

                $helper = new Plugin\Locale();
                $helper->setLocaleService($localeService);

                return $helper;
            },
    ]
];
