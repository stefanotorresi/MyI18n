<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\DataMapper;

interface TranslationMapperAwareInterface
{
    /**
     * @return TranslationMapperInterface
     */
    public function getTranslationMapper();

    /**
     * @param TranslationMapperInterface $translationMapper
     */
    public function setTranslationMapper(TranslationMapperInterface $translationMapper);
}
