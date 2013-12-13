<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18nTest\DataMapper;

use MyI18n\DataMapper\LocaleMapperAwareInitializer;
use MyI18nTest\TestAsset;

class LocaleMapperAwareInitializerTest extends \PHPUnit_Framework_TestCase
{
    public function testInitialize()
    {
        $instance = new TestAsset\LocaleMapperAwareInstance;

        $serviceLocator = $this->getMock('Zend\ServiceManager\ServiceManager');
        $localeMapper = $this->getMockBuilder('MyI18n\DataMapper\LocaleMapper')
            ->disableOriginalConstructor()->getMock();

        $serviceLocator->expects($this->atLeastOnce())
            ->method('get')
            ->with('MyI18n\DataMapper\LocaleMapper')
            ->will($this->returnValue($localeMapper));

        $initializer = new LocaleMapperAwareInitializer();
        $initializedInstance = $initializer->initialize($instance, $serviceLocator);

        $this->assertSame($instance, $initializedInstance);
        $this->assertSame($localeMapper, $instance->getLocaleMapper());
    }

    public function testDoNothingWithNonAwareClasses()
    {
        $instance = new \stdClass();

        $serviceLocator = $this->getMock('Zend\ServiceManager\ServiceManager');

        $serviceLocator->expects($this->never())
            ->method('get');

        $initializer = new LocaleMapperAwareInitializer();
        $initializedInstance = $initializer->initialize($instance, $serviceLocator);
        $this->assertSame($instance, $initializedInstance);
    }
}
