<?php

namespace MyI18n\Detector;

use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;

class Session extends AbstractDetector 
    implements 
        PersistCapableInterface, 
        ServiceManagerAwareInterface
{   
    /**
     *
     * @var ServiceManager 
     */
    protected $services;
    
    /**
     * 
     * @param MvcEvent $e
     * @return string
     */
    public function getLocale(MvcEvent $e)
    {        
        $session = $this->services->get('MyI18n\Session');
        
        $query = $session->{$this->config['key_name']};
        
        if ($query) {
            return $this->lookup($query);
        }
    }
    
    public function persist($locale)
    {
        $session = $this->services->get('MyI18n\Session');
        $session->{$this->config['key_name']} = $locale;
    }
    
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->services = $serviceManager;
    }
}