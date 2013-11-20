<?php

namespace MyI18n;

use Doctrine\ORM\EntityManager;
use Zend\Mvc\Router\Http\TreeRouteStack;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\Container as Session;

return [
    'factories' => [
        'MyI18n\LocaleStrategy' => function (ServiceLocatorInterface $serviceLocator) {
                $options = $serviceLocator->get('MyI18n\Options\ModuleOptions');
                $localeStrategy = new LocaleStrategy($options);

                return $localeStrategy;
            },
        'MyI18n\Service\LocaleService' => function (ServiceLocatorInterface $serviceLocator) {
                /** @var EntityManager $entityManager */
                $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
                $localeService = new Service\LocaleService($entityManager);

                return $localeService;
            },
        'MyI18n\Form\LocaleForm' => function (ServiceLocatorInterface $serviceLocator) {
                $form = new Form\LocaleForm();

                $router = $serviceLocator->get('router');

                // makes functional test possible
                if ($router instanceof TreeRouteStack) {
                    $form->setAttribute('action', $router->assemble([], ['name' => 'admin/i18n/locales/process']));
                }

                return $form;
            },
        'MyI18n\Options\ModuleOptions' => function (ServiceLocatorInterface $serviceLocator) {
                $moduleConfig = $serviceLocator->get('config')[__NAMESPACE__];
                $moduleOptions = new Options\ModuleOptions($moduleConfig);

                return $moduleOptions;
            },
        'MyI18n\Navigation' => 'MyI18n\NavigationFactory',
    ],

    'initializers' => [
        function($instance, ServiceLocatorInterface $serviceLocator) {
            if ($instance instanceof Service\LocaleServiceAwareInterface) {
                /** @var Service\LocaleService $localeService */
                $localeService = $serviceLocator->get('MyI18n\Service\LocaleService');
                $instance->setLocaleService($localeService);
            }
            return $instance;
        }
    ],

    'abstract_factories' => [
        'MyI18n\Detector\AbstractDetectorFactory',
    ],

    'services' => [
        'MyI18n\Session' => new Session(__NAMESPACE__),
    ],

    'aliases' => [
        'nav-lang' => 'MyI18n\Navigation',
        'translator' => 'MvcTranslator',
        'MyI18n\Service\Locale' => 'MyI18n\Service\LocaleService',
        'MyI18n\Form\Locale' => 'MyI18n\Form\LocaleForm',
    ],
];
