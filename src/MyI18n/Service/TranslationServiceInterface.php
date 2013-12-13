<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\Service;

use MyI18n\Entity\TranslatableInterface;

interface TranslationServiceInterface
{
    public function getTranslationMapper();
    public function translate(TranslatableInterface $entity, $translations);
    public function changeLocale(TranslatableInterface $entity, $locale);
}
