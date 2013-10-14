<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18nTest\TestAsset;

use MyI18n\Entity\Translation;
use MyI18n\Service\TranslationService;

class Translations
{
    public static function getTranslations()
    {

        $translation = new Translation();
        $translation->setId(1)->setDomain('test')->setLocale('it')->setMsgid('foo')->setMsgstr('bar');

        $translation1 = new Translation();
        $translation1->setId(2)->setDomain('test')->setLocale('it')->setMsgid('hello')->setMsgstr('ciao');

        $translation2 = new Translation();
        $translation2->setId(3)->setDomain('test-2')->setLocale('it')->setMsgid('bye')->setMsgstr('derci');

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
