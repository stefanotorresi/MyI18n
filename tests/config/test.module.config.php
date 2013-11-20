<?php

namespace MyI18n;

return [
    __NAMESPACE__ => [
    ],

    'doctrine' => array(
        'driver' => [
            __NAMESPACE__ => [
                'cache' => 'array',
            ],
        ],
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOSqlite\Driver',
                'params' => array(
                    'memory' => true,
                ),
            ),
        ),
        'configuration' => array(
            'orm_default' => array(
                'generate_proxies'  => true,
                'metadata_cache'    => 'array',
                'query_cache'       => 'array',
                'result_cache'      => 'array',
            ),
        ),
    ),

];
