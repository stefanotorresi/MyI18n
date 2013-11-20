<?php

/**
 *
 * @author Stefano Torresi <webdeveloper@stefanotorresi.it>
 */

namespace MyI18n\Detector;

use Locale;
use MyI18n\Options\DetectorOptionsInterface;
use MyI18n\Service\LocaleServiceAwareInterface;
use MyI18n\Service\LocaleServiceAwareTrait;

abstract class AbstractDetector implements
    DetectorInterface,
    LocaleServiceAwareInterface
{
    use LocaleServiceAwareTrait;

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
        return Locale::lookup($this->getLocaleService()->getAllCodesAsArray(), $locale);
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
