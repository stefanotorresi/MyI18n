<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

use MyI18n\DataMapper;
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

                /** @var DataMapper\LocaleMapper $localeMapper */
                $localeMapper = $serviceManager->get('MyI18n\Mapper\LocaleMapper');

                $helper = new Plugin\Locale();
                $helper->setLocaleMapper($localeMapper);

                return $helper;
            },
    ]
];
