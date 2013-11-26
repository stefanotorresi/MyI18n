<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n;

return [
    'doctrine' => [
        'eventmanager' => [
            'orm_default' => [
                'subscribers' => [
                    'MyI18n\Listener\TranslatableListener',
                ],
            ],
        ],
        'driver' => [
            __NAMESPACE__ => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => [__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity']
            ],
            'orm_default' =>[
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__,
                ],
            ],
        ],
    ],
];
