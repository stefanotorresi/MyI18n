<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\Listener;

use Gedmo\Translatable\TranslatableListener as GedmoTranslatableListener;
use MyI18n\Entity\Locale;
use MyI18n\Service;
use Zend\ServiceManager;

class TranslatableListener extends GedmoTranslatableListener implements
    ServiceManager\ServiceLocatorAwareInterface,
    Service\LocaleServiceAwareInterface
{
    use Service\LocaleServiceAwareTrait;
    use ServiceManager\ServiceLocatorAwareTrait;

    /**
     * @var bool $_initialized
     */
    protected $initialized = false;

    /**
     * @param ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function __construct(ServiceManager\ServiceLocatorInterface $serviceLocator)
    {
        $this->setTranslationFallback(true);
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

        return parent::getDefaultLocale();
    }

    /**
     * {@inheritdoc}
     */
    public function getListenerLocale()
    {
        if (! $this->initialized) {
            $this->initialize();
        }

        return parent::getListenerLocale();
    }

    /**
     * {@inheritdoc}
     */
    public function getTranslatableLocale($object, $meta)
    {
        if (! $this->initialized) {
            $this->initialize();
        }

        return parent::getTranslatableLocale($object, $meta);
    }

    /**
     *
     */
    private function initialize()
    {
        $defaultLocale = $this->getLocaleService()->getDefaultLocale();

        if ($defaultLocale instanceof Locale) {
            $defaultLocaleCode = $defaultLocale->getCode();

            $this->setDefaultLocale($defaultLocaleCode);
        }

        // this is actually not the 'default' locale but the current one
        $this->setTranslatableLocale(\Locale::getDefault());

        $this->initialized = true;
    }
}
