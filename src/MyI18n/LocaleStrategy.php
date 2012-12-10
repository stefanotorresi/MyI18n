<?php

/**
 *
 * @author Stefano Torresi <webdeveloper@stefanotorresi.it>
 */

namespace MyI18n;

use Locale;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\JsonModel;

class LocaleStrategy implements ListenerAggregateInterface
{
    /**
     *
     * @var array
     */
    protected $listeners = array();

    /**
     *
     * @var string
     */
    protected $locale;

    /**
     *
     * @var string
     */
    protected $config;

    /**
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     *
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
     *
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
     *
     * @param MvcEvent $e
     */
    public function detectLocale(MvcEvent $e)
    {
        if ($this->locale) {
            return;
        }

        $app        = $e->getApplication();
        $services   = $app->getServiceManager();
        $translator = $services->get('MyI18n\Translator');
        $handlers   = $this->config['handlers'];

        foreach ($handlers as $handlerName) {
            $handler = $services->get($handlerName);

            $locale = $handler->getLocale($e);

            if ($locale) {
                break;
            }
        }

        if (!isset($locale)) {
            $locale = $this->config['default'];
        }

        Locale::setDefault($locale);
        $translator->setLocale($locale);
        $this->locale = $locale;
    }

    public function persistLocale(MvcEvent $e)
    {
        $app        = $e->getApplication();
        $services   = $app->getServiceManager();
        $handlers   = $this->config['handlers'];

        if ($this->locale) {
            foreach ($handlers as $handlerName) {

                $handler = $services->get($handlerName);

                if ($handler instanceof Detector\PersistCapableInterface) {
                    $handler->persist($this->locale);
                }
            }
        }
    }

    /**
     *
     * @param MvcEvent $e
     */
    public function updateViewModel(MvcEvent $e)
    {
        $model = $e->getViewModel();
        if (!$model instanceof JsonModel) {
            $model->setVariable($this->config['key_name'], $this->locale);
        }
    }

    /**
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }
}
