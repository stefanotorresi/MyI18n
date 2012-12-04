<?php

namespace MyI18n;

return array(
    'Zend\Loader\ClassMapAutoloader' => array(
        __DIR__ . '/../autoload_classmap.php'
    ),
    'Zend\Loader\StandardAutoloader' => array(
        'namespaces' => array(
            __NAMESPACE__  => __DIR__ . '/../src/' . __NAMESPACE__,
            'My' => __DIR__ . '/../../../library/My',
        ),
    ),
);
