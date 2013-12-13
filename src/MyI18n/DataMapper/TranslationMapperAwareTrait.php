<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\DataMapper;

trait TranslationMapperAwareTrait
{
    /**
     * @var TranslationMapperInterface $localeMapper
     */
    protected $translationMapper;

    /**
     * @return TranslationMapperInterface
     */
    public function getTranslationMapper()
    {
        return $this->translationMapper;
    }

    /**
     * @param TranslationMapperInterface $translationMapper
     */
    public function setTranslationMapper(TranslationMapperInterface $translationMapper = null)
    {
        $this->translationMapper = $translationMapper;
    }
}
