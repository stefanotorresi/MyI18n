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

    'navigation' => [
        'backend' => [
            'my-i18n' => [
                'label' => 'Languages',
                'route' => 'admin/i18n',
            ],
        ],
    ],

    'view_manager' => [
        'template_map' => [
            'my-i18n/translation/index' => __DIR__ . '/../view/index.phtml',
            'my-i18n/translation-form' => __DIR__ . '/../view/translation-form.phtml',
        ],
    ],

    'controllers' => [
        'invokables' => [
            'MyI18n\Controller\LocaleController' => 'MyI18n\Controller\LocaleController',
        ],
        'aliases' => [
            'MyI18n\Controller\Locale' => 'MyI18n\Controller\LocaleController',
        ],
    ],

    'service_manager' => [
        'factories' => [
            'MyI18n\Navigation' => 'MyI18n\NavigationFactory',
        ],
        'invokables' => [
            'MyI18n\Detector\Query' => 'MyI18n\Detector\Query',
            'MyI18n\Detector\Session' => 'MyI18n\Detector\Session',
            'MyI18n\Detector\Route' => 'MyI18n\Detector\Route',
            'MyI18n\Detector\Headers' => 'MyI18n\Detector\Headers',
            'MyI18n\Form\LocaleForm' => 'MyI18n\Form\LocaleForm',
        ],
        'aliases' => [
            'nav-lang' => 'MyI18n\Navigation',
            'translator' => 'MvcTranslator',
            'MyI18n\Service\Locale' => 'MyI18n\Service\LocaleService',
            'MyI18n\Form\Locale' => 'MyI18n\Form\LocaleForm',
        ],
    ],

    'translator' => [
        'translation_file_patterns' => [
            [
                'type' => 'phpArray',
                'base_dir'      => __DIR__ . '/../language',
                'pattern'       => '%s/'.__NAMESPACE__.'.php',
                'text_domain'   => 'MyBackend',
            ],
        ],
    ],
];
