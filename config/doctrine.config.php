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
                    'Gedmo\Translatable\TranslatableListener',
                ],
            ],
        ],
        'driver' => [
            __NAMESPACE__ => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => [__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity']
            ],
//            'translatable_metadata_driver' => [
//                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
//                'cache' => 'array',
//                'paths' => [
//                    './vendor/gedmo/doctrine-extensions/lib/Gedmo/Translatable/Entity',
//                ],
//            ],
            'orm_default' =>[
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__,
//                    'Gedmo\Translatable\Entity' => 'translatable_metadata_driver',
                ],
            ],
        ],
    ],
];
