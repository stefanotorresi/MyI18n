<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18nTest\Listener;

use Doctrine\Common\EventManager;
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

        $serviceLocator = $this->getMock('Zend\ServiceManager\ServiceManager');
        $serviceLocator->expects($this->any())
            ->method('get')
            ->with('MyI18n\Service\LocaleService')
            ->will($this->returnValue($localeService));

        $this->realListener = new TranslatableListener;

        $this->listenerProxy = new TranslatableListenerProxy($this->realListener, $serviceLocator);
    }

    public function testDoesNotInitializeOnAttachment()
    {
        $this->assertAttributeEquals(false, 'initialized', $this->listenerProxy);

        $eventManager = new EventManager();
        $eventManager->addEventSubscriber($this->listenerProxy);

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
