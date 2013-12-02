<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18nTest\Service;

use MyI18n\Service\LocaleServiceAwareInitializer;
use MyI18nTest\TestAsset;

class LocaleServiceAwareInitializerTest extends \PHPUnit_Framework_TestCase
{
    public function testInitialize()
    {
        $instance = new TestAsset\LocaleServiceAwareInstance;

        $serviceLocator = $this->getMock('Zend\ServiceManager\ServiceManager');
        $localeService = $this->getMockBuilder('MyI18n\Service\LocaleService')
            ->disableOriginalConstructor()->getMock();

        $serviceLocator->expects($this->atLeastOnce())
            ->method('get')
            ->with('MyI18n\Service\LocaleService')
            ->will($this->returnValue($localeService));

        $initializer = new LocaleServiceAwareInitializer();
        $initializedInstance = $initializer->initialize($instance, $serviceLocator);

        $this->assertSame($instance, $initializedInstance);
        $this->assertSame($localeService, $instance->getLocaleService());
    }

    public function testDoNothingWithNonAwareClasses()
    {
        $instance = new \stdClass();

        $serviceLocator = $this->getMock('Zend\ServiceManager\ServiceManager');

        $serviceLocator->expects($this->never())
            ->method('get');

        $initializer = new LocaleServiceAwareInitializer();
        $initializedInstance = $initializer->initialize($instance, $serviceLocator);
        $this->assertSame($instance, $initializedInstance);
    }
}
