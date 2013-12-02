<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\Service;

use Locale as IntlLocale;
use MyI18n\Entity\Locale;

trait LocaleHelperTrait
{
    use LocaleServiceAwareTrait;

    public function __invoke()
    {
        return $this;
    }

    public function getCurrent()
    {
        return IntlLocale::getDefault();
    }

    /**
     * proxy to LocaleService->getDefault()
     *
     * @param  bool               $returnObject
     * @return string|Locale|null
     */
    public function getDefault($returnObject = true)
    {
        $locale = $this->getLocaleService()->getDefaultLocale();

        if ($returnObject || ! $locale instanceof Locale) {
            return $locale;
        }

        return $locale->getCode();
    }
}
