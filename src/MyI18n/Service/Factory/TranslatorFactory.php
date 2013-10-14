<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\Service\Factory;

use Zend\I18n\Translator\TranslatorServiceFactory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TranslatorFactory extends TranslatorServiceFactory implements FactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $translator = parent::createService($serviceLocator);
        $translator->enableEventManager();

        $translationService = $serviceLocator->get('MyI18n\Service\TranslationService');

        $translator->getEventManager()->attach('missingTranslation', array($translationService, 'addMissingTranslation'));
        $translator->getPluginManager()->setService('MyI18n\Service\TranslationService', $translationService);

        foreach ($translationService->getAllDomains() as $domain) {
            $translator->addRemoteTranslations('MyI18n\Service\TranslationService', $domain);
        }

        return $translator;
    }
}
