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
use Zend\Navigation\Navigation;
use Zend\Navigation\Page\Mvc as MvcPage;
use Zend\View\Model\JsonModel;

class LocaleStrategy implements ListenerAggregateInterface
{   
    const DEFAULT_LOCALE    = 'en';
    const DEFAULT_KEY_NAME  = 'lang';
    
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
        // a bit of config validation
        
        if (!isset($config['supported']) || !is_array($config['supported'])) {
            $config['supported'] = array();
        }
        
        if (!isset($config['default']) || empty($config['default']) 
                || !in_array($config['default'], $config['supported'])) {
            $config['default'] = self::DEFAULT_LOCALE;
        }
        
        if (!isset($config['key_name']) || empty($config['key_name'])) {
            $config['key_name'] = self::DEFAULT_KEY_NAME;
        }
        
        if (!isset($config['handlers']) || !is_array($config['handlers'])) {
            $config['handlers'] = array();
        }
        
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
        
        $this->listeners[] = $events->attach(MvcEvent::EVENT_RENDER, 
                array($this, 'createNavigation'), 1);
        
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
     * @param MvcEvent $e
     */
    public function createNavigation(MvcEvent $e)
    {
        $router     = $e->getRouter();
        $match      = $e->getRouteMatch();
        $services   = $e->getApplication()->getServiceManager();
            
        $pages = array();

        foreach ($this->config['supported'] as $locale) {
            $page = new MvcPage(array(
                'label'     => ucfirst(Locale::getDisplayLanguage($locale, $this->locale)),
                'params'    => array( $this->config['key_name'] => $locale ),
                'type'      => 'mvc',
                'route'     => 'lang-switch',
                'rel'       => array('alternate' => 'alternate'),
            ));

            $page->setDefaultRouter($router);

            if ($match) {
                $page->setRouteMatch($match);
            }
            
            if ($locale == $this->locale) {
                $page->setActive(true);
                $page->setOrder(-1);
            }

            $pages[] = $page;
        }
        
        if (!empty($pages)) {
            $services->setService('MyI18n\Navigation', new Navigation($pages));
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
