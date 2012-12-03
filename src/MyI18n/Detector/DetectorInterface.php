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
     * @param array $config
     */
    public function setConfig(array $config);

    /**
     *
     * @param string $locale
     */
    public function lookup($locale);
}
