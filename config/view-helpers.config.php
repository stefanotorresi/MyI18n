<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

use MyI18n\Service;
use MyI18n\View\Helper;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ServiceLocatorInterface;

return [
    'factories' => [
        'langTabs' => function (ServiceLocatorInterface $serviceLocator) {
                $serviceManager = $serviceLocator instanceof AbstractPluginManager ?
                    $serviceLocator->getServiceLocator()
                    : $serviceLocator;

                /** @var Service\LocaleService $localeService */
                $localeService = $serviceManager->get('MyI18n\Service\LocaleService');

                $helper = new Helper\LangTabs($localeService);

                return $helper;
            },
    ]
];
