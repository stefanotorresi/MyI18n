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
    public static function getLocales()
    {
        return [
            new Locale('en', 1),
            new Locale('de', 2),
            new Locale('it', 3),
        ];
    }

    public static function populateService(LocaleService $service)
    {
        foreach (static::getLocales() as $t) {
            $service->save($t);
        }
    }
}
