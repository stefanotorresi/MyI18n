<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\Listener;

use Gedmo\Translatable\TranslatableListener;
use MyI18n\Service\LocaleService;
use MyI18n\Service\LocaleServiceAwareTrait;

class TranslatableListenerProxy extends TranslatableListener
{
    use LocaleServiceAwareTrait;

    /**
     * @var TranslatableListener $listener
     */
    protected $realListener;

    /**
     * @var bool $_initialized
     */
    protected $initialized = false;

    /**
     * @param TranslatableListener $listener
     * @param LocaleService        $localeService
     */
    public function __construct(TranslatableListener $listener, LocaleService $localeService)
    {
        $this->realListener = $listener;
        $this->localeService = $localeService;
    }

    public function getDefaultLocale()
    {
        if (! $this->initialized) {
            $this->initialize();
        }

        return $this->realListener->getDefaultLocale();
    }

    public function getListenerLocale()
    {
        if (! $this->initialized) {
            $this->initialize();
        }

        return $this->realListener->getListenerLocale();
    }

    public function getTranslatableLocale($object, $meta)
    {
        if (! $this->initialized) {
            $this->initialize();
        }

        return $this->realListener->getTranslatableLocale($object, $meta);
    }

    private function initialize()
    {
        $defaultLocale = $this->getLocaleService()->getDefaultLocale()->getCode();

        $this->realListener->setDefaultLocale($defaultLocale);
        $this->realListener->setTranslatableLocale($defaultLocale);

        $this->initialized = true;
    }
}
