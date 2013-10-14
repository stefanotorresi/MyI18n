<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18nTest\TestAsset;

use MyI18n\Entity\Locale;
use MyI18n\Entity\Translation;
use MyI18n\Service\TranslationService;

class Translations
{
    public static function getTranslations()
    {
        $locale = new Locale('it');
        $locale->setId(1);

        $translation = new Translation();
        $translation->setId(1)->setDomain('test')->setLocale($locale)->setMsgid('foo')->setMsgstr('bar');

        $translation1 = new Translation();
        $translation1->setId(2)->setDomain('test')->setLocale($locale)->setMsgid('hello')->setMsgstr('ciao');

        $translation2 = new Translation();
        $translation2->setId(3)->setDomain('test-2')->setLocale($locale)->setMsgid('bye')->setMsgstr('derci');

        return [
            $translation,
            $translation1,
            $translation2,
        ];
    }

    public static function populateService(TranslationService $service)
    {
        foreach (static::getTranslations() as $t) {
            $service->save($t);
        }
    }
}
