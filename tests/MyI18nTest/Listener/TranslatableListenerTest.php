<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18nTest\Listener;

use Doctrine\Common\EventManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use MyI18n\Entity\Locale;
use MyI18n\Listener\TranslatableListener;

class TranslatableListenerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TranslatableListener $listener
     */
    protected $listener;

    public function setUp()
    {
        $localeService = $this->getMockBuilder('MyI18n\Service\LocaleService')
            ->disableOriginalConstructor()->getMock();

        $serviceLocator = $this->getMock('Zend\ServiceManager\ServiceManager');
        $serviceLocator->expects($this->any())
            ->method('get')
            ->with('MyI18n\Service\LocaleService')
            ->will($this->returnValue($localeService));

        $this->listener = new TranslatableListener($serviceLocator);
    }

    public function testDoesNotInitializeOnAttachment()
    {
        $this->assertAttributeEquals(false, 'initialized', $this->listener);

        $eventManager = new EventManager();
        $eventManager->addEventSubscriber($this->listener);

        $this->assertAttributeEquals(false, 'initialized', $this->listener);
    }

    /**
     * @dataProvider dataInitializedMethods
     *
     * @param $method
     * @param $args
     */
    public function testInitializationBeforeGetter($method, $args)
    {
        $this->assertAttributeEquals(false, 'initialized', $this->listener);

        $defaultLocale = new Locale('en');

        $this->listener->getLocaleService()
            ->expects($this->once())
            ->method('getDefaultLocale')
            ->will($this->returnValue($defaultLocale));

        call_user_func_array([$this->listener, $method], $args);

        $this->assertAttributeEquals(true, 'initialized', $this->listener);
        $this->assertSame($defaultLocale->getCode(), $this->listener->getDefaultLocale());
        $this->assertSame(\Locale::getDefault(), $this->listener->getListenerLocale());
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
