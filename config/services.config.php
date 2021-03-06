<?php

namespace MyI18n;

use Doctrine\ORM\EntityManager;
use Gedmo\Translatable\TranslatableListener;
use Zend\Mvc\I18n\Translator;
use Zend\Mvc\Router\Http\TreeRouteStack;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\Container as Session;

return [
    'factories' => [
        'MyI18n\Listener\LocaleAggregateListener' =>
            function (ServiceLocatorInterface $serviceLocator) {
                /** @var Options\ModuleOptions $options */
                $options = $serviceLocator->get('MyI18n\Options\ModuleOptions');

                /** @var DataMapper\LocaleMapper $localeMapper */
                $localeMapper = $serviceLocator->get('MyI18n\DataMapper\LocaleMapper');

                /** @var Translator $translator */
                $translator         = $serviceLocator->get('translator');

                /** @var TranslatableListener $doctrineListener */
                $doctrineListener = $serviceLocator->get('Gedmo\Translatable\TranslatableListener');

                $localeStrategy = new Listener\LocaleAggregateListener($options);
                $localeStrategy->setLocaleMapper($localeMapper);
                $localeStrategy->setTranslator($translator);
                $localeStrategy->setDoctrineListener($doctrineListener);

                return $localeStrategy;
            },
        'MyI18n\DataMapper\LocaleMapper' =>
            function (ServiceLocatorInterface $serviceLocator) {
                /** @var EntityManager $entityManager */
                $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

                return $entityManager->getRepository(Entity\Locale::fqcn());
            },
        'MyI18n\Form\LocaleForm' =>
            function (ServiceLocatorInterface $serviceLocator) {
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
        'MyI18n\Options\ModuleOptions' =>
            function (ServiceLocatorInterface $serviceLocator) {
                $moduleConfig = $serviceLocator->get('config')[__NAMESPACE__];
                $moduleOptions = new Options\ModuleOptions($moduleConfig);

                return $moduleOptions;
            },
        'MyI18n\Navigation' => 'MyI18n\Navigation\NavigationFactory',
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
    ],
];
