<?php

namespace MyI18n;
use Zend\Session\Container as Session;

return array(    
    'factories' => array(
        'MyI18n\Translator' => 'Zend\I18n\Translator\TranslatorServiceFactory',
        'MyI18n\LocaleStrategy' => function($services) {
            $config = $services->get('config');
            $instance = new LocaleStrategy($config[__NAMESPACE__]);
            return $instance;
        },
    ),
    'initializers' => array(
        function($instance, $services) {
            if ($instance instanceof Detector\AbstractDetector) {
                $config = $services->get('MyI18n\LocaleStrategy')->getConfig();
                $instance->setConfig($config);
            }
        }
    ),
    'invokables' => array (
        'MyI18n\Detector\Query'   => 'MyI18n\Detector\Query',
        'MyI18n\Detector\Session'   => 'MyI18n\Detector\Session',
        'MyI18n\Detector\Route'   => 'MyI18n\Detector\Route',
        'MyI18n\Detector\Headers'   => 'MyI18n\Detector\Headers',
    ),
    'services' => array(
        'MyI18n\Session' => new Session(__NAMESPACE__),
    ),
);
