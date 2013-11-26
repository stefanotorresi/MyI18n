<?php

/**
 *
 * @author Stefano Torresi <webdeveloper@stefanotorresi.it>
 */

namespace MyI18n\Listener;

use Locale;
use MyI18n\Detector;
use MyI18n\Options;
use MyI18n\Service;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\Exception\ExtensionNotLoadedException;

class LocaleAggregateListener extends AbstractListenerAggregate
    implements Service\LocaleServiceAwareInterface
{
    use Service\LocaleServiceAwareTrait;

    /**
     * @var boolean
     */
    protected $localeDetected = false;

    /**
     * @var Options\ModuleOptions
     */
    protected $moduleOptions;

    /**
     * @param  Options\ModuleOptions       $options
     * @param  Service\LocaleService       $localeService
     * @throws ExtensionNotLoadedException
     */
    public function __construct(Options\ModuleOptions $options, Service\LocaleService $localeService)
    {
        if (! extension_loaded('intl')) {
            throw new ExtensionNotLoadedException(sprintf(
                '%s requires the intl PHP extension',
                __CLASS__
            ));
        }

        $this->setModuleOptions($options);
        $this->setLocaleService($localeService);
    }

    /**
     * @return Options\ModuleOptions
     */
    public function getModuleOptions()
    {
        return $this->moduleOptions;
    }

    /**
     * @param Options\ModuleOptions $moduleOptions
     */
    public function setModuleOptions(Options\ModuleOptions $moduleOptions)
    {
        $this->moduleOptions = $moduleOptions;
    }

    /**
     * @param EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events)
    {

        $this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE,
                array($this, 'detectLocale'), -1);

        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH_ERROR,
                array($this, 'detectLocale'), 100);

        $this->listeners[] = $events->attach(MvcEvent::EVENT_FINISH,
                array($this, 'persistLocale'), -1);
    }

    /**
     * @param MvcEvent $e
     */
    public function detectLocale(MvcEvent $e)
    {
        if ($this->localeDetected) {
            return;
        }

        $app                = $e->getApplication();
        $serviceManager     = $app->getServiceManager();
        $translator         = $serviceManager->get('translator');
        $detectors          = $this->getModuleOptions()->getDetectors();

        foreach ($detectors as $detectorServiceName) {
            $detector = $serviceManager->get($detectorServiceName);

            $locale = $detector->getLocale($e);

            if ($locale) {
                break;
            }
        }

        if (!isset($locale)) {
            $locale = $this->getLocaleService()->getDefaultLocale()->getCode();
        }

        Locale::setDefault($locale);
        $translator->setLocale($locale);
        $this->localeDetected = true;
    }

    /**
     * @param MvcEvent $e
     */
    public function persistLocale(MvcEvent $e)
    {
        $app        = $e->getApplication();
        $services   = $app->getServiceManager();
        $detectors   = $this->getModuleOptions()->getDetectors();

        if ($this->localeDetected) {
            foreach ($detectors as $detectorServiceName) {
                $detector = $services->get($detectorServiceName);

                if (! $detector instanceof Detector\PersistCapableInterface) {
                    continue;
                }

                $detector->persist(Locale::getDefault());
            }
        }
    }
}
