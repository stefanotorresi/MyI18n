<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18nTest\TestAsset;

use MyI18n\Entity\Locale;
use MyI18n\DataMapper\LocaleMapperInterface;

class Locales
{
    protected static $locales;

    public static function getLocales()
    {
        return static::$locales;
    }

    public static function populateMapper(LocaleMapperInterface $mapper)
    {
        static::initLocales();

        $locales = static::getLocales();
        foreach ($locales as $key => $locale) {
            $mapper->save($locale);
            $locales[$key]->setId($key+1);
        }
    }

    private static function initLocales()
    {
        $de = new Locale('de');
        $en = new Locale('en');
        $it = new Locale('it');
        $it->setDefaultLocale();

        // note the alphabetical order
        // it is assumed to be this way by testGetAll()
        static::$locales = [$de, $en, $it];
    }
}
