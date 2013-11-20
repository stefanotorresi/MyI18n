<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18nTest\TestAsset;

use MyI18n\Entity\Locale;
use MyI18n\Service\LocaleService;

class Locales
{
    public static $locales;

    public static function getLocales()
    {
        if (empty(static::$locales)) {
            $de = new Locale('de');
            $en = new Locale('en');
            $it = new Locale('it');
            $it->setDefaultLocale();

            // note the alphabetical order
            // it is assumed to be this way by testGetAll()
            static::$locales = [$de, $en, $it];
        }

        return static::$locales;
    }

    public static function populateService(LocaleService $service)
    {
        $locales = static::getLocales();
        foreach ($locales as $key => &$locale) {
            $service->save($locale);
            $locale->setId($key+1);
        }
    }
}
