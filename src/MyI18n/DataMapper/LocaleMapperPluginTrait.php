<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\DataMapper;

use Locale as IntlLocale;
use MyI18n\Entity\Locale;

trait LocaleMapperPluginTrait
{
    use LocaleMapperAwareTrait;

    public function __invoke()
    {
        return $this;
    }

    public function getCurrent()
    {
        return IntlLocale::getDefault();
    }

    /**
     * proxy to LocaleMapper->getDefault()
     *
     * @param  bool               $returnObject
     * @return string|Locale|null
     */
    public function getDefault($returnObject = true)
    {
        $locale = $this->getLocaleMapper()->findDefaultLocale();

        if ($returnObject || ! $locale instanceof Locale) {
            return $locale;
        }

        return $locale->getCode();
    }
}
