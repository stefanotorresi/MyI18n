<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\Service;

trait LocaleServiceAwareTrait
{
    /**
     * @var LocaleService $localeService
     */
    protected $localeService;

    /**
     * @return LocaleService
     */
    public function getLocaleService()
    {
        return $this->localeService;
    }

    public function setLocaleService(LocaleService $localeService = null)
    {
        $this->localeService = $localeService;
    }
}
