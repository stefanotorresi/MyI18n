<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n;

return [
    __NAMESPACE__ => [
        'default'   => 'en',
        'supported' => [],
        'fallback'  => '',
        'handlers' => [
            'MyI18n\Detector\Query',
            'MyI18n\Detector\Route',
            'MyI18n\Detector\Session',
            'MyI18n\Detector\Headers',
        ],
        'key_name'  => 'lang',
        'navigation' => [
            // possible values: true, false, 'only_active'
            'full_lang_as_label' => true,
            'query_uri' => false,
        ],
    ],

    'service_manager' => [
        'factories' => [
            'MyI18n\Translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
            'MyI18n\Navigation' => 'MyI18n\NavigationFactory',
        ],
        'invokables' => [
            'MyI18n\Detector\Query'   => 'MyI18n\Detector\Query',
            'MyI18n\Detector\Session'   => 'MyI18n\Detector\Session',
            'MyI18n\Detector\Route'   => 'MyI18n\Detector\Route',
            'MyI18n\Detector\Headers'   => 'MyI18n\Detector\Headers',
        ],
        'aliases' => [
            'nav-lang' => 'MyI18n\Navigation',
            'translator' => 'MyI18n\Translator',
        ],
    ],

    'router' => [
        'routes' => [
            'lang-switch' => [
                'type'    => 'segment',
                'options' => [
                    'route'    => '/:lang',
                    'defaults' => [
                        'controller' => 'index',
                        'action'     => 'index'
                    ],
                    'constraints' => [
                        'lang' => '[a-z]{2}',
                    ],
                ],
                'may_terminate' => true,
            ],
        ],
    ],
];
