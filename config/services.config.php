<?php

namespace MyI18n;

use Zend\Session\Container as Session;

return [
    'factories' => [
        'MyI18n\LocaleStrategy' => function($serviceLocator) {
            $config = $serviceLocator->get('config');
            $instance = new LocaleStrategy($config[__NAMESPACE__]);

            return $instance;
        },
    ],
    'initializers' => [
        function($instance, $serviceLocator) {
            if ($instance instanceof Detector\AbstractDetector) {
                $config = $serviceLocator->get('MyI18n\LocaleStrategy')->getConfig();
                $instance->setConfig($config);
            }
        }
    ],
    'services' => [
        'MyI18n\Session' => new Session(__NAMESPACE__),
    ],
];
