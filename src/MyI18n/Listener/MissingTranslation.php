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

    /**
     * @var array
     */
    protected $domainsBlackList = array();

    /**
     * @var array
     */
    protected $domainsWhiteList = array();

    /**
     * @var array
     */
    protected $localesBlackList = array();

    /**
     * @var array
     */
    protected $localesWhiteList = array();

    /**
     * @param Service\TranslationService $translationService
     * @param Service\LocaleService      $localeService
     */
    public function __construct(Service\TranslationService $translationService, Service\LocaleService $localeService)
    {
        $this->translationService = $translationService;
        $this->localeService = $localeService;
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
        $textDomain = $event->getParam('text_domain');
        $localeCode = $event->getParam('locale');

        if ((! empty($this->localesBlackList) && in_array($localeCode, $this->localesBlackList))
            || (! empty($this->localesWhiteList) && ! in_array($localeCode, $this->localesWhiteList))
            || (! empty($this->domainsBlackList) && in_array($textDomain, $this->domainsBlackList))
            || (! empty($this->domainsWhiteList) && ! in_array($textDomain, $this->domainsWhiteList))
        ) {
            return;
        }

        if ($this->getTranslationService()->findTranslation($message, $textDomain)) {
            return;
        }

        $translation = new Entity\Translation();
        $translation->setMsgid($message);
        $translation->setTextDomain($textDomain);

        if (! $locale = $this->getLocaleService()->findOneByCode($localeCode)) {
            $locale = new Entity\Locale($localeCode);
        }
        $translation->setLocale($locale);

        $this->getTranslationService()->save($translation);
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

    public function getListeners()
    {
        return $this->listeners;
    }

    /**
     * @return array
     */
    public function getDomainsBlackList()
    {
        return $this->domainsBlackList;
    }

    /**
     * @param array $domainsBlackList
     * @return $this
     */
    public function setDomainsBlackList(array $domainsBlackList)
    {
        $this->domainsBlackList = $domainsBlackList;

        return $this;
    }

    /**
     * @return array
     */
    public function getDomainsWhiteList()
    {
        return $this->domainsWhiteList;
    }

    /**
     * @param array $domainsWhileList
     * @return $this
     */
    public function setDomainsWhiteList(array $domainsWhileList)
    {
        $this->domainsWhiteList = $domainsWhileList;

        return $this;
    }

    /**
     * @return array
     */
    public function getLocalesBlackList()
    {
        return $this->localesBlackList;
    }

    /**
     * @param array $localesBlackList
     * @return $this
     */
    public function setLocalesBlackList(array $localesBlackList)
    {
        $this->localesBlackList = $localesBlackList;

        return $this;
    }

    /**
     * @return array
     */
    public function getLocalesWhiteList()
    {
        return $this->localesWhiteList;
    }

    /**
     * @param array $localesWhileList
     * @return $this
     */
    public function setLocalesWhiteList(array $localesWhileList)
    {
        $this->localesWhiteList = $localesWhileList;

        return $this;
    }
}
