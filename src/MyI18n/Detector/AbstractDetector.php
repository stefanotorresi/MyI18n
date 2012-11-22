<?php

/**
 *
 * @author Stefano Torresi <webdeveloper@stefanotorresi.it>
 */

namespace MyI18n\Detector;

use Locale;

abstract class AbstractDetector implements DetectorInterface
{
    /**
     *
     * @var array
     */
    protected $config;
    
    public function setConfig(array $config)
    {
        $this->config = $config;
    }
    
    /**
     * 
     * @param string $locale
     * @return string
     */
    public function lookup($locale)
    {
        return Locale::lookup($this->config['supported'], $locale);
    }
}
