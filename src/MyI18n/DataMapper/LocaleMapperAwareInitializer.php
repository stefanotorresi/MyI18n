<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\DataMapper;

use Zend\ServiceManager\InitializerInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LocaleMapperAwareInitializer implements InitializerInterface
{
    /**
     * Initialize
     *
     * @param $instance
     * @param  ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function initialize($instance, ServiceLocatorInterface $serviceLocator)
    {
        if ($instance instanceof LocaleMapperAwareInterface) {
            /** @var LocaleMapper $localeMapper */
            $localeMapper = $serviceLocator->get('MyI18n\DataMapper\LocaleMapper');
            $instance->setLocaleMapper($localeMapper);
        }

        return $instance;
    }
}
