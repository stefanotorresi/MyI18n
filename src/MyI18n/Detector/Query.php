<?php

namespace MyI18n\Detector;

use Zend\Mvc\MvcEvent;

class Query extends AbstractDetector
{
    /**
     * 
     * @param MvcEvent $e
     * @return string
     */
    public function getLocale(MvcEvent $e)
    {        
        $request = $e->getRequest();
        
        $query = $request->getQuery($this->config['key_name']);
        
        if ($query) {
            return $this->lookup($query);
        }
    }
}