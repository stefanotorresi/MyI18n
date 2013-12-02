<?php

/**
 *
 * @author Stefano Torresi <webdeveloper@stefanotorresi.it>
 */

namespace MyI18n\Listener;

use Gedmo\Translatable\TranslatableListener;
use Locale;
use MyI18n\Detector;
use MyI18n\Options;
use MyI18n\Service;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\I18n\Translator\TranslatorAwareTrait;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\Exception\ExtensionNotLoadedException;

class LocaleAggregateListener extends AbstractListenerAggregate
    implements Service\LocaleServiceAwareInterface
{
    use Service\LocaleServiceAwareTrait;
    use TranslatorAwareTrait;

    /**
     * @var boolean
     */
    protected $localeDetected = false;

    /**
     * @var Options\ModuleOptions
     */
    protected $moduleOptions;

    /**
     * @var TranslatableListener $doctrineListener
     */
    protected $doctrineListener;

    /**
     * @param  Options\ModuleOptions       $options
     * @throws ExtensionNotLoadedException
     */
    public function __construct(Options\ModuleOptions $options)
    {
        if (! extension_loaded('intl')) {
            throw new ExtensionNotLoadedException(sprintf(
                '%s requires the intl PHP extension',
                __CLASS__
            ));
        }

        $this->setModuleOptions($options);
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
     * @return TranslatableListener
     */
    public function getDoctrineListener()
    {
        return $this->doctrineListener;
    }

    /**
     * @param TranslatableListener $doctrineListener
     */
    public function setDoctrineListener($doctrineListener)
    {
        $this->doctrineListener = $doctrineListener;
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

        $detectors          = $this->getModuleOptions()->getDetectors();

        foreach ($detectors as $detectorServiceName) {
            $detector = $serviceManager->get($detectorServiceName);

            $locale = $detector->getLocale($e);

            if ($locale) {
                break;
            }
        }

        $defaultLocaleEntity = $this->getLocaleService()->getDefaultLocale();
        $defaultLocale = $defaultLocaleEntity instanceof \MyI18n\Entity\Locale ?
            $defaultLocaleEntity->getCode() : Locale::getDefault();

        $this->getDoctrineListener()->setDefaultLocale($defaultLocale);
        $this->getDoctrineListener()->setTranslationFallback(true);

        if (!isset($locale)) {
            $locale = $defaultLocale;
        }

        Locale::setDefault($locale);
        $this->getTranslator()->setLocale($locale);
        $this->getDoctrineListener()->setTranslatableLocale($locale);
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
