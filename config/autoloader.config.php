<?php

namespace MyI18n;

return array(
    'Zend\Loader\StandardAutoloader' => array(
        'namespaces' => array(
            __NAMESPACE__  => __DIR__ . '/../src/' . __NAMESPACE__,
            'My' => __DIR__ . '/../../../library/My',
        ),
    ),
);
