<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

return [
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

            'admin' => [
                'child_routes' => [
                    'i18n' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/i18n',
                            'defaults' => [
                                'controller' => 'MyI18n\Controller\LocaleController',
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'locales' => [
                                'type' => 'Literal',
                                'options' => [
                                    'route' => '/locales',
                                    'defaults' => [
                                        'controller' => 'MyI18n\Controller\LocaleController',
                                        'action' => 'index',
                                    ],
                                ],
                                'may_terminate' => true,
                                'child_routes' => [
                                    'enable' => [
                                        'type' => 'Literal',
                                        'options' => [
                                            'route' => '/enable',
                                            'defaults' => [
                                                'action' => 'enable',
                                            ],
                                        ],
                                    ],
                                    'disable' => [
                                        'type' => 'Segment',
                                        'options' => [
                                            'route' => '/disable/:code',
                                            'defaults' => [
                                                'action' => 'disable',
                                            ],
                                            'constraints' => [
                                                'code' => '[a-zA-Z]{2}',
                                            ],
                                        ],
                                    ],
                                    'make-default' => [
                                        'type' => 'Segment',
                                        'options' => [
                                            'route' => '/make-default/:code',
                                            'defaults' => [
                                                'action' => 'make-default',
                                            ],
                                            'constraints' => [
                                                'code' => '[a-zA-Z]{2}',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
