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
        'enable_backend' => false,
        'enable_missing_translation_listener' => false
    ],

    'navigation' => [
        'backend' => [
            'my-i18n' => [
                'label' => 'Internationalization',
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
            'MyI18n\Controller\Translation' => 'MyI18n\Controller\TranslationController',
        ],
    ],

    'service_manager' => [
        'factories' => [
            'MyI18n\Service\TranslationService' => 'MyI18n\Service\Factory\TranslationServiceFactory',
            'MyI18n\Form\TranslationForm' => 'MyI18n\Service\Factory\TranslationFormFactory',
            'MyI18n\Translator' => 'MyI18n\Service\Factory\TranslatorFactory',
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

    'translator' => array(
        'translation_file_patterns' => array(
            array(
                'type' => 'phpArray',
                'base_dir'      => __DIR__ . '/../language',
                'pattern'       => '%s/'.__NAMESPACE__.'.php',
                'text_domain'   => 'MyBackend',
            ),
        ),
    ),
];
