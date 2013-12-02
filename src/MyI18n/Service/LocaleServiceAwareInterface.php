<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\Service;

interface LocaleServiceAwareInterface
{
    /**
     * @return LocaleService
     */
    public function getLocaleService();

    /**
     * @param LocaleService $localeService
     */
    public function setLocaleService(LocaleService $localeService);
}
