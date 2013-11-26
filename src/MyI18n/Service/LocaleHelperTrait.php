<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\Service;

use Locale;

trait LocaleHelperTrait
{
    use LocaleServiceAwareTrait;

    /**
     * @param LocaleService $localeService
     */
    public function __construct(LocaleService $localeService)
    {
        $this->setLocaleService($localeService);
    }

    public function __invoke()
    {
        return $this;
    }

    public function getCurrent()
    {
        return Locale::getDefault();
    }

    public function getDefault($asString = true)
    {
        $locale = $this->getLocaleService()->getDefaultLocale();

        if ($asString && $locale instanceof \MyI18n\Entity\Locale) {
            return $locale->getCode();
        }

        return $locale;
    }
}
