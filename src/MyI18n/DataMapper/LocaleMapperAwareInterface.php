<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\DataMapper;

interface LocaleMapperAwareInterface
{
    /**
     * @return LocaleMapperInterface
     */
    public function getLocaleMapper();

    /**
     * @param LocaleMapperInterface $localeMapper
     */
    public function setLocaleMapper(LocaleMapperInterface $localeMapper);
}
