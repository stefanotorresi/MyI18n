<?php

/**
 *
 * @author Stefano Torresi <webdeveloper@stefanotorresi.it>
 */

namespace MyI18n;

use Locale;
use MyI18n\Service\LocaleServiceAwareInterface;
use MyI18n\Service\LocaleServiceAwareTrait;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\JsonModel;

class LocaleStrategy implements
    ListenerAggregateInterface,
    LocaleServiceAwareInterface
{
    use LocaleServiceAwareTrait;

    /**
     * @var array
     */
    protected $listeners = array();

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var Options\ModuleOptions
     */
    protected $moduleOptions;

    /**
     * @param Options\ModuleOptions $options
     */
    public function __construct(Options\ModuleOptions $options)
    {
        $this->moduleOptions = $options;
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

        $this->listeners[] = $events->attach(MvcEvent::EVENT_RENDER,
                array($this, 'updateViewModel'), 1);

        $this->listeners[] = $events->attach(MvcEvent::EVENT_FINISH,
                array($this, 'persistLocale'), -1);
    }

    /**
     * @param EventManagerInterface $events
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    /**
     * @param MvcEvent $e
     */
    public function detectLocale(MvcEvent $e)
    {
        if ($this->locale) {
            return;
        }

        $app                = $e->getApplication();
        $serviceManager     = $app->getServiceManager();
        $translator         = $serviceManager->get('translator');
        $detectors          = $this->moduleOptions->getDetectors();

        foreach ($detectors as $detectorServiceName) {
            $detector = $serviceManager->get($detectorServiceName);

            $locale = $detector->getLocale($e);

            if ($locale) {
                break;
            }
        }

        if (!isset($locale)) {
            $locale = $this->getLocaleService()->getDefaultLocale();
        }

        Locale::setDefault($locale);
        $translator->setLocale($locale);
        $this->locale = $locale;
    }

    /**
     * @param MvcEvent $e
     */
    public function persistLocale(MvcEvent $e)
    {
        $app        = $e->getApplication();
        $services   = $app->getServiceManager();
        $detectors   = $this->moduleOptions->getDetectors();

        if ($this->locale) {
            foreach ($detectors as $detectorServiceName) {

                $detector = $services->get($detectorServiceName);

                if ($detector instanceof Detector\PersistCapableInterface) {
                    $detector->persist($this->locale);
                }
            }
        }
    }

    /**
     * @param MvcEvent $e
     */
    public function updateViewModel(MvcEvent $e)
    {
        $model = $e->getViewModel();
        if (!$model instanceof JsonModel) {
            $model->setVariable($this->moduleOptions->getKeyName(), $this->locale);
        }
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
}
