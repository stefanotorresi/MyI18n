<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\Listener;

use Gedmo\Translatable\TranslatableListener;
use MyI18n\Entity\Locale;
use MyI18n\Service;
use Zend\ServiceManager;

class TranslatableListenerProxy extends TranslatableListener implements
    ServiceManager\ServiceLocatorAwareInterface,
    Service\LocaleServiceAwareInterface
{
    use Service\LocaleServiceAwareTrait;
    use ServiceManager\ServiceLocatorAwareTrait;

    /**
     * @var TranslatableListener $listener
     */
    protected $realListener;

    /**
     * @var bool $_initialized
     */
    protected $initialized = false;

    /**
     * @param TranslatableListener                   $listener
     * @param ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function __construct(TranslatableListener $listener, ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        $this->realListener = $listener;
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * {@inheritdoc}
     */
    public function getLocaleService()
    {
        if (! $this->localeService) {
            $this->localeService = $this->getServiceLocator()->get('MyI18n\Service\LocaleService');
        }

        return $this->localeService;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultLocale()
    {
        if (! $this->initialized) {
            $this->initialize();
        }

        return $this->realListener->getDefaultLocale();
    }

    /**
     * {@inheritdoc}
     */
    public function getListenerLocale()
    {
        if (! $this->initialized) {
            $this->initialize();
        }

        return $this->realListener->getListenerLocale();
    }

    /**
     * {@inheritdoc}
     */
    public function getTranslatableLocale($object, $meta)
    {
        if (! $this->initialized) {
            $this->initialize();
        }

        return $this->realListener->getTranslatableLocale($object, $meta);
    }

    /**
     *
     */
    private function initialize()
    {
        $defaultLocale = $this->getLocaleService()->getDefaultLocale();

        if ($defaultLocale instanceof Locale) {
            $defaultLocaleCode = $defaultLocale->getCode();

            $this->realListener->setDefaultLocale($defaultLocaleCode);
            $this->realListener->setTranslatableLocale($defaultLocaleCode);
        }

        $this->initialized = true;
    }
}
