<?php

namespace MyI18n\Detector;

use Zend\Mvc\MvcEvent;

class Route extends AbstractDetector
{
    /**
     * 
     * @param MvcEvent $e
     * @return string
     */
    public function getLocale(MvcEvent $e)
    {   
        $routeMatch = $e->getRouteMatch();
        
        if ($routeMatch) {
            
            $query = $routeMatch->getParam($this->config['key_name']);
        
            if ($query) {
                return $this->lookup($query);
            }
        }
    }
}