<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\DataMapper;

interface TranslationMapperInterface
{
    public function translate($entity, $field, $locale, $value);
    public function refresh($entity);
}
