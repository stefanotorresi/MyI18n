<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

return array(
    'router' => array(
        'routes' => array(

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

            'admin' => array(
                'child_routes' => array(
                    'i18n' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/i18n',
                            'defaults' => array(
                                'controller' => 'MyI18n\Controller\Locale',
                                'action' => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'locales' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/locales',
                                    'defaults' => array(
                                        'controller' => 'MyI18n\Controller\Locale',
                                        'action' => 'index',
                                    ),
                                ),
                                'may_terminate' => true,
                                'child_routes' => array(
                                    'enable' => array(
                                        'type' => 'Literal',
                                        'options' => array(
                                            'route' => '/enable',
                                            'defaults' => array(
                                                'action' => 'enable',
                                            ),
                                        ),
                                    ),
                                    'disable' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/disable/:code',
                                            'defaults' => array(
                                                'action' => 'disable',
                                            ),
                                            'constraints' => array(
                                                'code' => '[a-zA-Z]{2}',
                                            ),
                                        ),
                                    ),
                                    'make-default' => array(
                                        'type' => 'Segment',
                                        'options' => array(
                                            'route' => '/make-default/:code',
                                            'defaults' => array(
                                                'action' => 'make-default',
                                            ),
                                            'constraints' => array(
                                                'code' => '[a-zA-Z]{2}',
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);
