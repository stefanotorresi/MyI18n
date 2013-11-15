<?php

namespace MyI18n;

return array(
    __NAMESPACE__ => array(
        'default'   => 'en',
        'supported' => array(),
        'fallback'  => '',
        'handlers' => array(
            'MyI18n\Detector\Query',
            'MyI18n\Detector\Route',
            'MyI18n\Detector\Session',
            'MyI18n\Detector\Headers',
        ),
        'key_name'  => 'lang',
        'navigation' => array(
            // possible values: true, false, 'only_active'
            'full_lang_as_label' => true,
            'query_uri' => false,
        )
    ),

    'router' => array(
        'routes' => array(
            'lang-switch' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/:lang',
                    'defaults' => array(
                        'controller' => 'index',
                        'action'     => 'index'
                    ),
                    'constraints' => array(
                        'lang' => '[a-z]{2}',
                    ),
                ),
                'may_terminate' => true,
            ),
        ),
    ),

    'service_manager' => array(
        'factories' => array(
            'MvcTranslator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
            'MyI18n\Navigation' => 'MyI18n\NavigationFactory',
        ),
        'invokables' => array (
            'MyI18n\Detector\Query'   => 'MyI18n\Detector\Query',
            'MyI18n\Detector\Session'   => 'MyI18n\Detector\Session',
            'MyI18n\Detector\Route'   => 'MyI18n\Detector\Route',
            'MyI18n\Detector\Headers'   => 'MyI18n\Detector\Headers',
        ),
        'aliases' => array(
            'nav-lang' => 'MyI18n\Navigation',
            'translator' => 'MvcTranslator',
        ),
    ),
);
