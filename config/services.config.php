<?php

namespace MyI18n;

use Doctrine\ORM\EntityManager;
use Gedmo\Translatable\TranslatableListener;
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

                $defaultLocaleCode= $localeService->getDefaultLocale()->getCode();

                /** @var \Gedmo\Translatable\TranslatableListener $translatableSubscriber */
                $translatableSubscriber = $serviceLocator->get('Gedmo\Translatable\TranslatableListener');
                $translatableSubscriber->setDefaultLocale($defaultLocaleCode);
                $translatableSubscriber->setTranslatableLocale($defaultLocaleCode);

                return $localeService;
            },
        'MyI18n\Form\LocaleForm' => function (ServiceLocatorInterface $serviceLocator) {

                $router = $serviceLocator->get('router');
                $hydrator = $serviceLocator->get('HydratorManager')->get('DoctrineModule\Stdlib\Hydrator\DoctrineObject');

                $form = new Form\LocaleForm();
                $form->setHydrator($hydrator)->setObject(new Entity\Locale());

                // wrap in an if to makes functional testing possible
                if ($router instanceof TreeRouteStack) {
                    $form->setAttribute('action', $router->assemble([], ['name' => 'admin/i18n/locales/enable']));
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

    'invokables' => [
        'Gedmo\Translatable\TranslatableListener' => 'Gedmo\Translatable\TranslatableListener',
    ],

    'initializers' => [
        function ($instance, ServiceLocatorInterface $serviceLocator) {
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
