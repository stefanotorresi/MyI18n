<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18nTest;

use DoctrineORMModuleTest\Util\ServiceManagerFactory;
use PHPUnit_Framework_TestCase as TestCase;

class ModuleFunctionalTest extends TestCase
{
    protected $services = [
        'MyI18n\Form\TranslationForm',
        'MyI18n\Listener\MissingTranslation',
        'MyI18n\Service\LocaleService',
        'MyI18n\Service\TranslationService',
    ];

    public function testServiceManagerConfiguration()
    {
        $serviceManager = ServiceManagerFactory::getServiceManager();

        foreach($this->services as $service) {
            $this->assertInstanceOf($service, $serviceManager->get($service));
        }
    }
}
