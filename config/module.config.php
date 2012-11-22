<?php

namespace MyI18n;

return array(
    __NAMESPACE__ => array(
        'default'   => 'en',
        'supported' => array('en', 'it', 'fr'),
        'fallback'  => '',
        'handlers' => array(
            __NAMESPACE__.'\Detector\Query',
            __NAMESPACE__.'\Detector\Route',
            __NAMESPACE__.'\Detector\Session',
            __NAMESPACE__.'\Detector\Headers',
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
