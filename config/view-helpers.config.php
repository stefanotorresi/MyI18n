<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

use MyI18n\DataMapper;
use MyI18n\View\Helper;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\ServiceLocatorInterface;

return [
    'factories' => [
        'langTabs' => function (ServiceLocatorInterface $serviceLocator) {
                $serviceManager = $serviceLocator instanceof AbstractPluginManager ?
                    $serviceLocator->getServiceLocator()
                    : $serviceLocator;

                /** @var DataMapper\LocaleMapper $localeMapper */
                $localeMapper = $serviceManager->get('MyI18n\DataMapper\LocaleMapper');

                $helper = new Helper\LangTabs($localeMapper);

                return $helper;
            },
        'locale' => function (ServiceLocatorInterface $serviceLocator) {
                $serviceManager = $serviceLocator instanceof AbstractPluginManager ?
                    $serviceLocator->getServiceLocator()
                    : $serviceLocator;

                /** @var DataMapper\LocaleMapper $localeMapper */
                $localeMapper = $serviceManager->get('MyI18n\DataMapper\LocaleMapper');

                $helper = new Helper\Locale();
                $helper->setLocaleMapper($localeMapper);

                return $helper;
            },
    ]
];
