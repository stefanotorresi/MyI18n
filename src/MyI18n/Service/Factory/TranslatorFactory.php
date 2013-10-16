<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\Service\Factory;

use MyI18n\Service\TranslationService;
use Zend\Mvc\Service\TranslatorServiceFactory;
use Zend\ServiceManager\ServiceLocatorInterface;

class TranslatorFactory extends TranslatorServiceFactory
{
    /**
     * {@inheritdoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $translator = parent::createService($serviceLocator);
        $config = $serviceLocator->get('config')['MyI18n'];

        /** @var TranslationService $translationService */
        $translationService = $serviceLocator->get('MyI18n\Service\TranslationService');
        $translator->getPluginManager()->setService('MyI18n\Service\TranslationService', $translationService);

        foreach ($translationService->getAllDomains() as $domain) {
            $translator->addRemoteTranslations('MyI18n\Service\TranslationService', $domain);
        }

        if ($config['enable_missing_translation_listener']) {
            $translator->enableEventManager();
            $translator->getEventManager()->attach('missingTranslation', array($translationService, 'missingTranslationListener'));
        }

        return $translator;
    }
}
