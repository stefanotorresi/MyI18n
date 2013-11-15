<?php

namespace MyI18n\Detector;

use Zend\Http\Request;
use Zend\Mvc\MvcEvent;

class Headers extends AbstractDetector
{
    /**
     * @param  MvcEvent $e
     * @return string
     */
    public function getLocale(MvcEvent $e)
    {
        $request = $e->getRequest();

        if (! $request instanceof Request) {
            return;
        }

        $headers = $request->getHeaders();

        if ($headers->has('Accept-Language')) {
            $acceptHeaders = $headers->get('Accept-Language')->getPrioritized();

            foreach ($acceptHeaders as $header) {
                if ($locale = $this->lookup($header->getLanguage())) {
                    return $locale;
                }
            }
        }
    }
}
