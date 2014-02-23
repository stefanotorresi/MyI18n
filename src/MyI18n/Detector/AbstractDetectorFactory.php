<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\Detector;

use MyI18n\DataMapper;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AbstractDetectorFactory implements AbstractFactoryInterface
{
    protected $detectorClasses = [
        'MyI18n\Detector\Query',
        'MyI18n\Detector\Session',
        'MyI18n\Detector\Route',
        'MyI18n\Detector\Headers',
    ];

    /**
     * Determine if we can create a service with name
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return bool
     */
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        if (in_array($requestedName, $this->detectorClasses) && class_exists($requestedName)) {
            return true;
        }

        return false;
    }

    /**
     * Create service with name
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @throws \DomainException
     * @return DetectorInterface
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        /** @var \MyI18n\Options\ModuleOptions $moduleOptions */
        $moduleOptions = $serviceLocator->get('MyI18n\Options\ModuleOptions');

        $detector = new $requestedName($moduleOptions);

        if (! $detector instanceof DetectorInterface) {
           throw new \DomainException(sprintf(
               '%s is not a valid DetectorInterface class',
               get_class($detector)
           ));
        }

        if ($detector instanceof SessionAwareInterface) {
            /** @var \Zend\Session\Container $session */
            $session = $serviceLocator->get('MyI18n\Session');
            $detector->setSession($session);
        }

        if ($detector instanceof DataMapper\LocaleMapperAwareInterface) {
            /** @var DataMapper\LocaleMapper $localeMapper */
            $localeMapper = $serviceLocator->get('MyI18n\DataMapper\LocaleMapper');
            $detector->setLocaleMapper($localeMapper);
        }

        return $detector;
    }
}
