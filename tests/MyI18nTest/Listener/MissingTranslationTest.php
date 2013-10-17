<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18nTest\Listener;

use MyI18n\Entity;
use MyI18n\Listener\MissingTranslation;
use MyI18nTest\EntityManagerAwareFunctionalTestTrait;
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
            ->setDomain($event->getParam('text_domain'))
            ->setLocale(new Entity\Locale($event->getParam('locale')));

        $listener->getTranslationService()
            ->expects($this->once())
            ->method('save')
            ->with($translation);

        $listener->addMissingTranslations($event);
    }
}
