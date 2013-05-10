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
            // either shows full language name as navigation labels or the shortcode (i.e. English if true, EN if false)
            'full_lang_as_label' => true
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
);
