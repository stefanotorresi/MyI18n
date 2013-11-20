<?php

namespace MyI18n\Detector;

use Zend\Mvc\MvcEvent;

interface DetectorInterface
{
    /**
     *
     * @param MvcEvent $e
     */
    public function getLocale(MvcEvent $e);

    /**
     *
     * @param string $locale
     */
    public function lookup($locale);
}
