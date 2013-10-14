<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

use Zend\Stdlib\ArrayUtils;

$crud = array(
    'create' => array(
        'type' => 'Literal',
        'options' => array(
            'route' => '/create',
            'defaults' => array(
                'action' => 'create',
            ),
        ),
    ),
    'read' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/:id',
            'defaults' => array(
                'action' => 'get',
            ),
            'constraints' => array(
                'id' => '\d+'
            ),
        ),
    ),
    'update' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/update/:id',
            'defaults' => array(
                'action' => 'update',
            ),
            'constraints' => array(
                'id' => '\d+'
            ),
        ),
    ),
    'delete' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/delete/:id',
            'defaults' => array(
                'action' => 'delete',
            ),
            'constraints' => array(
                'id' => '\d+'
            ),
        ),
    ),
);

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
            'admin' => array(
                'child_routes' => array(
                    'i18n' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/i18n',
                            'defaults' => array(
                                'controller' => 'MyI18n\Controller\Translation',
                                'action' => 'index',
                            ),
                        ),
                        'may_terminate' => true,
                        'child_routes' => array(
                            'translations' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/translations',
                                    'defaults' => array(
                                        'controller' => 'MyBlog\Controller\Translation',
                                    ),
                                ),
                                'child_routes' => ArrayUtils::merge($crud, $paged)
                            ),
                            'locales' => array(
                                'type' => 'Literal',
                                'options' => array(
                                    'route' => '/locales',
                                    'defaults' => array(
                                        'controller' => 'MyBlog\Controller\Locale',
                                        'action' => 'index',
                                    ),
                                ),
                                'may_terminate' => true,
                                'child_routes' => ArrayUtils::merge($crud, $paged)
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);
