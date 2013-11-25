<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18nTest;

use PHPUnit_Framework_TestCase as TestCase;

class ModuleFunctionalTest extends TestCase
{
    /**
     * @dataProvider servicesProvider
     * @param $locatorInstance
     * @param $name
     * @param $class
     */
    public function testServiceManagerConfiguration($locatorInstance, $name, $class)
    {
        $serviceLocator = $locatorInstance == 'ServiceManager' ?
            Bootstrap::getServiceManager() :
            Bootstrap::getServiceManager()->get($locatorInstance);

        $this->assertTrue($serviceLocator->has($name));
        $this->assertInstanceOf($class, $serviceLocator->get($name));
    }

    public function servicesProvider()
    {
        return array(
            array('ServiceManager', 'MyI18n\Service\LocaleService', 'MyI18n\Service\LocaleService'),
            array('ServiceManager', 'MyI18n\Service\Locale', 'MyI18n\Service\LocaleService'),
            array('ServiceManager', 'MyI18n\Form\LocaleForm', 'MyI18n\Form\LocaleForm'),
            array('ServiceManager', 'MyI18n\Form\Locale', 'MyI18n\Form\LocaleForm'),
            array('ServiceManager', 'Gedmo\Translatable\TranslatableListener', 'MyI18n\Listener\TranslatableListenerProxy'),
            array('FormElementManager', 'MyI18n\Form\LocaleFieldset', 'MyI18n\Form\LocaleFieldset'),
            array('ControllerLoader', 'MyI18n\Controller\LocaleController', 'MyI18n\Controller\LocaleController'),
            array('ControllerLoader', 'MyI18n\Controller\Locale', 'MyI18n\Controller\LocaleController'),
        );
    }
}
