<?php

namespace MyI18n;

use Doctrine\ORM\EntityManager;
use MyI18n\Form\LocaleForm;
use Zend\Mvc\Router\Http\TreeRouteStack;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\Container as Session;

return [
    'factories' => [
        'MyI18n\LocaleStrategy' => function(ServiceLocatorInterface $serviceLocator)
            {
                $config = $serviceLocator->get('config')[__NAMESPACE__];
                $localeStrategy = new LocaleStrategy($config);

                return $localeStrategy;
            },
        'MyI18n\Service\LocaleService' => function(ServiceLocatorInterface $serviceLocator)
            {
                /** @var EntityManager $entityManager */
                $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
                $localeService = new Service\LocaleService($entityManager);

                return $localeService;
            },
        'MyI18n\Form\LocaleForm' => function(ServiceLocatorInterface $serviceLocator)
            {
                $form = new LocaleForm();

                $router = $serviceLocator->get('router');

                // makes functional test possible
                if ($router instanceof TreeRouteStack) {
                    $form->setAttribute('action', $router->assemble([], ['name' => 'admin/i18n/locales/process']));
                }

                return $form;
            }
    ],

    'initializers' => [
        function($instance, ServiceLocatorInterface $serviceLocator) {
            if (! $instance instanceof Detector\AbstractDetector) {
                return;
            }
            $config = $serviceLocator->get('MyI18n\LocaleStrategy')->getConfig();
            $instance->setConfig($config);
        }
    ],

    'services' => [
        'MyI18n\Session' => new Session(__NAMESPACE__),
    ],
];
