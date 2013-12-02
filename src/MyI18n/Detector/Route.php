<?php

namespace MyI18n\Detector;

use Zend\Mvc\MvcEvent;

class Route extends AbstractDetector
{
    /**
     *
     * @param  MvcEvent $e
     * @return string
     */
    public function getLocale(MvcEvent $e)
    {
        $routeMatch = $e->getRouteMatch();

        if ($routeMatch) {

            $param = $routeMatch->getParam($this->getOptions()->getKeyName());

            if ($param) {
                return $this->lookup($param);
            }
        }
    }
}
