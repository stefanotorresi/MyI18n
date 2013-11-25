<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18nTest\Listener;

use Doctrine\ORM\Mapping\ClassMetadata;
use Gedmo\Translatable\TranslatableListener;
use MyI18n\Entity\Locale;
use MyI18n\Listener\TranslatableListenerProxy;

class TranslatableListenerProxyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TranslatableListenerProxy $listenerProxy
     */
    protected $listenerProxy;

    /**
     * @var TranslatableListener $realListener
     */
    protected $realListener;

    public function setUp()
    {
        $localeService = $this->getMockBuilder('MyI18n\Service\LocaleService')
            ->disableOriginalConstructor()->getMock();

        $this->realListener = new TranslatableListener;

        $this->listenerProxy = new TranslatableListenerProxy($this->realListener, $localeService);
    }

    public function testDoesNotInitializeOnAttachment()
    {
        $this->assertAttributeEquals(false, 'initialized', $this->listenerProxy);

        $events = $this->listenerProxy->getSubscribedEvents();

        $this->assertAttributeEquals(false, 'initialized', $this->listenerProxy);
    }

    /**
     * @dataProvider dataInitializedMethods
     *
     * @param $method
     * @param $args
     */
    public function testInitializationBeforeGetter($method, $args)
    {
        $this->assertAttributeEquals(false, 'initialized', $this->listenerProxy);

        $defaultLocale = new Locale('en');

        $this->listenerProxy->getLocaleService()
            ->expects($this->once())
            ->method('getDefaultLocale')
            ->will($this->returnValue($defaultLocale));

        $listenerLocale = call_user_func_array([$this->listenerProxy, $method], $args);

        $this->assertAttributeEquals(true, 'initialized', $this->listenerProxy);
        $this->assertSame($defaultLocale->getCode(), $listenerLocale);
    }

    public function dataInitializedMethods()
    {
        return [
            ['getDefaultLocale', []],
            ['getListenerLocale', []],
            ['getTranslatableLocale', [new \stdClass, new ClassMetadata('boh')]],
        ];
    }
}
