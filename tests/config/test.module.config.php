<?php

namespace MyI18n;

return [
    __NAMESPACE__ => [
    ],

    'doctrine' => [
        'driver' => [
            __NAMESPACE__ => [
                'cache' => 'array',
            ],
            'test_driver' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => [__DIR__ . '/../MyI18nTest/TestAsset']
            ],
            'orm_default' => [
                'drivers' => [
                    'MyI18nTest\TestAsset' => 'test_driver'
                ],
            ],
        ],
        'connection' => [
            'orm_default' => [
                'driverClass' => 'Doctrine\DBAL\Driver\PDOSqlite\Driver',
                'params' => [
                    'memory' => true,
                ],
            ],
        ],
        'configuration' => [
            'orm_default' => [
                'generate_proxies'  => true,
                'metadata_cache'    => 'array',
                'query_cache'       => 'array',
                'result_cache'      => 'array',
            ],
        ],
    ],

];
