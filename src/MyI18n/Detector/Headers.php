<?php

namespace MyI18n\Detector;

use Zend\Mvc\MvcEvent;

class Headers extends AbstractDetector
{
    /**
     *
     * @param  MvcEvent $e
     * @return string
     */
    public function getLocale(MvcEvent $e)
    {
        $headers = $e->getRequest()->getHeaders();

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
