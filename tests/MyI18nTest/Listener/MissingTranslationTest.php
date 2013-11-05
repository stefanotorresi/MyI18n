<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18nTest\Listener;

use MyI18n\Entity;
use MyI18n\Listener\MissingTranslation;
use Zend\EventManager\Event;
use PHPUnit_Framework_TestCase as TestCase;

class MissingTranslationTest extends TestCase
{
    /**
     * @var MissingTranslation
     */
    protected $listener;

    public function setUp()
    {
        $localeService = $this->getMock('MyI18n\Service\LocaleService', [], [], '', false);
        $translationService = $this->getMock('MyI18n\Service\TranslationService', [], [], '', false);

        $this->listener = new MissingTranslation($translationService, $localeService);
    }

    public function testServiceAccessors()
    {
        $listener = $this->listener;

        $anotherLocaleService = $this->getMock('MyI18n\Service\LocaleService', [], [], '', false);
        $anotherTranslationService = $this->getMock('MyI18n\Service\TranslationService', [], [], '', false);

        $listener->setTranslationService($anotherTranslationService)->setLocaleService($anotherLocaleService);

        $this->assertSame($anotherTranslationService, $listener->getTranslationService());
        $this->assertSame($anotherLocaleService, $listener->getLocaleService());
    }

    public function testAttach()
    {
        $eventManager       = $this->getMock('Zend\\EventManager\\EventManagerInterface');
        $callbackHandlers   = array();
        $test               = $this;

        $eventManager
            ->expects($this->once())
            ->method('attach')
            ->will($this->returnCallback(function () use (&$callbackHandlers, $test) {
                return $callbackHandlers[] = $test->getMock('Zend\\Stdlib\\CallbackHandler', [], [], '', false);
            }));

        $this->listener->attach($eventManager);
        $this->assertSame($callbackHandlers, $this->listener->getListeners());
    }

    public function testAddMissingTranslation()
    {
        $listener = $this->listener;

        $event = new Event;
        $event->setParam('message', 'foo');
        $event->setParam('text_domain', 'bar');
        $event->setParam('locale', 'test');

        $translation = new Entity\Translation();
        $translation
            ->setMsgid($event->getParam('message'))
            ->setTextDomain($event->getParam('text_domain'))
            ->setLocale(new Entity\Locale($event->getParam('locale')));

        $listener->getTranslationService()
            ->expects($this->once())
            ->method('save')
            ->with($translation);

        $listener->addMissingTranslations($event);
    }

    public function testLocalesBlackList()
    {
        $listener = $this->listener;
        $listener->setLocalesBlackList(['black_listed_locale']);

        $event = new Event;
        $event->setParam('message', 'foo');
        $event->setParam('text_domain', 'bar');
        $event->setParam('locale', 'black_listed_locale');

        $listener->getTranslationService()
            ->expects($this->never())
            ->method('save');

        $listener->addMissingTranslations($event);
    }

    public function testLocalesBlackListInverseCondition()
    {
        $listener = $this->listener;
        $listener->setLocalesBlackList(['black_listed_locale']);

        $event = new Event;
        $event->setParam('message', 'foo');
        $event->setParam('text_domain', 'bar');
        $event->setParam('locale', 'not_a_black_listed_locale');

        $translation = new Entity\Translation();
        $translation
            ->setMsgid($event->getParam('message'))
            ->setTextDomain($event->getParam('text_domain'))
            ->setLocale(new Entity\Locale($event->getParam('locale')));

        $listener->getTranslationService()
            ->expects($this->once())
            ->method('save')
            ->with($translation);

        $listener->addMissingTranslations($event);
    }

    public function testDomainsBlackList()
    {
        $listener = $this->listener;
        $listener->setDomainsBlackList(['black_listed_domain']);

        $event = new Event;
        $event->setParam('message', 'foo');
        $event->setParam('text_domain', 'black_listed_domain');
        $event->setParam('locale', 'test');

        $listener->getTranslationService()
            ->expects($this->never())
            ->method('save');

        $listener->addMissingTranslations($event);
    }

    public function testDomainsBlackListInverseCondition()
    {
        $listener = $this->listener;
        $listener->setDomainsBlackList(['black_listed_domain']);

        $event = new Event;
        $event->setParam('message', 'foo');
        $event->setParam('text_domain', 'not_a_black_listed_domain');
        $event->setParam('locale', 'test');

        $translation = new Entity\Translation();
        $translation
            ->setMsgid($event->getParam('message'))
            ->setTextDomain($event->getParam('text_domain'))
            ->setLocale(new Entity\Locale($event->getParam('locale')));

        $listener->getTranslationService()
            ->expects($this->once())
            ->method('save')
            ->with($translation);

        $listener->addMissingTranslations($event);
    }

    public function testLocalesBlackListPrecedesWhiteList()
    {
        $listener = $this->listener;
        $listener->setLocalesBlackList(['some_locale']);
        $listener->setLocalesWhiteList(['some_locale']);

        $event = new Event;
        $event->setParam('message', 'foo');
        $event->setParam('text_domain', 'bar');
        $event->setParam('locale', 'some_locale');

        $listener->getTranslationService()
            ->expects($this->never())
            ->method('save');

        $listener->addMissingTranslations($event);
    }

    public function testDomainsBlackListPrecedesWhiteList()
    {
        $listener = $this->listener;
        $listener->setDomainsBlackList(['some_domain']);
        $listener->setDomainsWhiteList(['some_domain']);

        $event = new Event;
        $event->setParam('message', 'foo');
        $event->setParam('text_domain', 'some_domain');
        $event->setParam('locale', 'some_locale');

        $listener->getTranslationService()
            ->expects($this->never())
            ->method('save');

        $listener->addMissingTranslations($event);
    }

    public function testLocalesBlackListPrecedesDomainsWhiteList()
    {
        $listener = $this->listener;
        $listener->setLocalesBlackList(['some_locale']);
        $listener->setDomainsWhiteList(['some_domain']);

        $event = new Event;
        $event->setParam('message', 'foo');
        $event->setParam('text_domain', 'some_domain');
        $event->setParam('locale', 'some_locale');

        $listener->getTranslationService()
            ->expects($this->never())
            ->method('save');

        $listener->addMissingTranslations($event);
    }
}
