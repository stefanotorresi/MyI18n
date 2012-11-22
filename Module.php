<?php
/**
 *
 * @author Stefano Torresi <webdeveloper@stefanotorresi.it>
 */

namespace MyI18n;

use Locale;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\ModuleManager;

class Module
{
    
    public function onBootstrap(MvcEvent $e)
    {
        $app = $e->getApplication();
        $events = $app->getEventManager();
        $services = $app->getServiceManager();
        
        /* @var $strategy DetectStrategy */
        $strategy = $services->get("MyI18n\DetectStrategy");
        
        $strategy->attach($events);
        
        // call initLocale before dispatch
//        $events->attach(
//            array(MvcEvent::EVENT_DISPATCH, MvcEvent::EVENT_DISPATCH_ERROR), 
//            array($this, 'initLocale'),
//            100
//        );
        
    }
    
    public function initLocale(MvcEvent $e)
    {   
        $match      = $e->getRouteMatch();
        $app        = $e->getApplication();
        $config     = $app->getConfig();
        $services   = $app->getServiceManager();
        
        /* @var $translator \Zend\I18n\Translator\Translator */
        $translator = $services->get('translator');
        
        /* @var $session \Zend\Session\Container */
        $session    = $services->get('session-i18n'); 
        
        $lang_config = $config['translator']['language'];
        
        $headers = $app->getRequest()->getHeaders();
        
        $lang_param = $match && $match->getParam('lang') ? 
                $match->getParam('lang') : 
                    (isset($session->lang) ? $session->lang : null);
        
        if (!$lang_param && $headers->has('Accept-Language')) {
            $locales = $headers->get('Accept-Language')->getPrioritized();
        } else {
            $locales[0] = $lang_param;
        }
        
        $lang = null;
        while ( ($locale = current($locales)) && !$lang) {            
            if (!is_string($locale)) {
                $locale = $locale->getLanguage();
            }
            
            $lang = Locale::lookup($lang_config['supported'], $locale);
            
            next($locales);
        }
        
        if (!$lang) {
            $lang = $lang_config['default'];
        }
        
        Locale::setDefault($lang);
        $translator->setLocale($lang);
        $session->lang = $lang;
        
        if (!empty($lang_config['fallback'])) {
            $translator->setFallbackLocale($lang_config['fallback']);
        }
        
        $view = $e->getViewModel();
        $view->setVariable('lang', $lang);
        
        if (!$services->has('nav-i18n')) {
            $services->setService('nav-i18n', new Navigation($e));
        }
        
    }
    
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return include __DIR__ . '/config/autoloader.config.php';
    }
    
    public function getServiceConfig()
    {
        return include __DIR__ . '/config/service.config.php';
    }
}

