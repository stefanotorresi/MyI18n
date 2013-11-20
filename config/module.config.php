<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n;

return [
    __NAMESPACE__ => [
//        'detectors' => [
//            'MyI18n\Detector\Query',
//            'MyI18n\Detector\Route',
//            'MyI18n\Detector\Session',
//            'MyI18n\Detector\Headers',
//        ],
//        'key_name'  => 'lang',
//        'navigation_options' => [
//            'label_display' => Options\NavigationOptions::LABEL_DISPLAY_FULL,
//            'queryString' => false,
//        ],
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
        'template_path_stack' => [
            __DIR__ . '/../view',
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
