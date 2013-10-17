<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\Listener;

use MyI18n\Entity;
use MyI18n\Service;
use Zend\EventManager\Event;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\I18n\Translator\Translator;

class MissingTranslation implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    /**
     * @var Service\TranslationService
     */
    protected $translationService;

    /**
     * @var Service\LocaleService
     */
    protected $localeService;

    public function __construct(Service\TranslationService $translationService, Service\LocaleService $localeService)
    {
        $this->translationService = $translationService;
        $this->localeService = $localeService;
    }

    /**
     * @return Service\TranslationService
     */
    public function getTranslationService()
    {
        return $this->translationService;
    }

    /**
     * @param Service\TranslationService $translationService
     * @return $this
     */
    public function setTranslationService(Service\TranslationService $translationService)
    {
        $this->translationService = $translationService;

        return $this;
    }

    /**
     * @return Service\LocaleService
     */
    public function getLocaleService()
    {
        return $this->localeService;
    }

    /**
     * @param Service\LocaleService $localeService
     * @return $this
     */
    public function setLocaleService(Service\LocaleService $localeService)
    {
        $this->localeService = $localeService;

        return $this;
    }

    /**
     * {@inerhitdoc}
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(Translator::EVENT_MISSING_TRANSLATION, [$this, 'addMissingTranslations']);
    }

    public function addMissingTranslations(Event $event)
    {
        $message = $event->getParam('message');
        $localeCode = $event->getParam('locale');
        $domain = $event->getParam('text_domain');

        if (! $this->getTranslationService()->findTranslation($message, $domain)) {
            $translation = new Entity\Translation();
            $translation->setMsgid($message);
            $translation->setDomain($domain);

            if (! $locale = $this->getLocaleService()->findOneByCode($localeCode)) {
                $locale = new Entity\Locale($localeCode);
            }
            $translation->setLocale($locale);

            $this->getTranslationService()->save($translation);
        }
    }

    public function getListeners()
    {
        return $this->listeners;
    }
}
