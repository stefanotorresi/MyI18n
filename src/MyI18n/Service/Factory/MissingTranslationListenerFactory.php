<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\Service\Factory;

use MyI18n\Service;
use MyI18n\Listener\MissingTranslation;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MissingTranslationListenerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return MissingTranslation
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var Service\TranslationService $translationService */
        $translationService = $serviceLocator->get('MyI18n\Service\TranslationService');

        /** @var Service\LocaleService $localeService */
        $localeService = $serviceLocator->get('MyI18n\Service\LocaleService');

        $listener = new MissingTranslation($translationService, $localeService);

        return $listener;
    }
}
