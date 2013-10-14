<?php

return [

    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOSqlite\Driver',
                'params' => array(
                    'path' =>  __DIR__ . '/../../test.db',
                ),
            ),
        ),
        'configuration' => array(
            'orm_default' => array(
                'generate_proxies'  => true,
            ),
        ),
    ),

];
