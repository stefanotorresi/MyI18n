<?php

namespace MyI18n;

use Doctrine\ORM\EntityManager;
use Gedmo\Translatable\TranslatableListener;
use MyI18n\Listener\TranslatableListenerProxy;
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

    'delegators' => [
        'Gedmo\Translatable\TranslatableListener' => [
            function (ServiceLocatorInterface $serviceLocator, $name, $requestedName, $callback) {
                /** @var TranslatableListener $translatableListener */
                $translatableListener = call_user_func($callback);

                /** @var Service\LocaleService $localeService */
                $localeService = $serviceLocator->get('MyI18n\Service\LocaleService');

                $translatableListener = new TranslatableListenerProxy($translatableListener, $localeService);

                return $translatableListener;
            }
        ],
    ],

    'services' => [
        'MyI18n\Session' => new Session(__NAMESPACE__),
    ],

    'abstract_factories' => [
        'MyI18n\Detector\AbstractDetectorFactory',
    ],

    'invokables' => [
        'Gedmo\Translatable\TranslatableListener' => 'Gedmo\Translatable\TranslatableListener',
    ],

    'aliases' => [
        'nav-lang' => 'MyI18n\Navigation',
        'translator' => 'MvcTranslator',
        'MyI18n\Service\Locale' => 'MyI18n\Service\LocaleService',
        'MyI18n\Form\Locale' => 'MyI18n\Form\LocaleForm',
    ],
];
