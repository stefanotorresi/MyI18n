<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\Detector;

use Locale;
use MyI18n\Options\DetectorOptionsInterface;
use MyI18n\Service;
use Zend\Stdlib\Exception\ExtensionNotLoadedException;

abstract class AbstractDetector implements
    DetectorInterface,
    Service\LocaleServiceAwareInterface
{
    use Service\LocaleServiceAwareTrait;

    /**
     *
     * @var array
     */
    protected $options;

    /**
     * @param  DetectorOptionsInterface    $options
     * @param  Service\LocaleService       $localeService
     * @throws ExtensionNotLoadedException
     */
    public function __construct(DetectorOptionsInterface $options = null, Service\LocaleService $localeService = null)
    {
        if (! extension_loaded('intl')) {
            throw new ExtensionNotLoadedException(sprintf(
                '%s requires the intl PHP extension',
                __CLASS__
            ));
        }

        $this->setOptions($options);
        $this->setLocaleService($localeService);
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

    /**
     *
     * @param  string $locale
     * @return string
     */
    public function lookup($locale)
    {
        return Locale::lookup($this->getLocaleService()->getAllCodesAsArray(), $locale);
    }
}
