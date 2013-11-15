<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

use Zend\Stdlib\ArrayUtils;

$paged = array(
    'paged' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/page-:page[-:itemsPerPage]',
            'constraints' => array(
                'page' => '\d+',
                'itemsPerPage' => '\d+',
            ),
        ),
    ),
);

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
                                'child_routes' => ArrayUtils::merge(
                                    array(
                                        'process' => array(
                                            'type' => 'Literal',
                                            'options' => array(
                                                'route' => '/process',
                                                'defaults' => array(
                                                    'action' => 'process',
                                                ),
                                            ),
                                        ),
                                    ),
                                    $paged
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);
