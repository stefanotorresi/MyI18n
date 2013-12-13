<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\DataMapper;

trait LocaleMapperAwareTrait
{
    /**
     * @var LocaleMapperInterface $localeMapper
     */
    protected $localeMapper;

    /**
     * @return LocaleMapperInterface
     */
    public function getLocaleMapper()
    {
        return $this->localeMapper;
    }

    /**
     * @param LocaleMapperInterface $localeMapper
     */
    public function setLocaleMapper(LocaleMapperInterface $localeMapper = null)
    {
        $this->localeMapper = $localeMapper;
    }
}
