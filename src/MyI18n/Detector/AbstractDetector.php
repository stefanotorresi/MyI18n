<?php

/**
 *
 * @author Stefano Torresi <webdeveloper@stefanotorresi.it>
 */

namespace MyI18n\Detector;

use Locale;
use MyI18n\Options\DetectorOptionsInterface;

abstract class AbstractDetector implements DetectorInterface
{
    /**
     *
     * @var array
     */
    protected $options;

    /**
     * @param DetectorOptionsInterface $options
     */
    public function __construct(DetectorOptionsInterface $options = null)
    {
        $this->options = $options;
    }

    /**
     *
     * @param  string $locale
     * @return string
     */
    public function lookup($locale)
    {
        return Locale::lookup($this->options->getSupportedLocales(), $locale);
    }

    /**
     * @return DetectorOptionsInterface
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param DetectorOptionsInterface $options
     */
    public function setOptions(DetectorOptionsInterface $options)
    {
        $this->options = $options;
    }
}
