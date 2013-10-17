<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18nTest\Service\Factory;

use Doctrine\ORM\EntityManager;
use MyI18n\Service\Factory\TranslatorFactory;
use PHPUnit_Framework_TestCase as TestCase;
use Zend\I18n\Translator\Translator;

class TranslatorFactoryTest extends TestCase
{
    public function testCreateService()
    {
        $translatorFactory = new TranslatorFactory();

        $config = [
            'MyI18n' => [
                'missing_translation_listener' => [
                    'enabled' => false,
                    'ignore_domains' => [],
                    'only_domains' => [],
                ],
            ],
        ];

        $serviceManager = $this->getServiceManagerMock($config);

        $translatorFactory->createService($serviceManager);
    }

    public function testMissingTranslationListenerEnabled()
    {
        $translatorFactory = new TranslatorFactory();

        $config = [
            'MyI18n' => [
                'missing_translation_listener' => [
                    'enabled' => true,
                    'ignore_domains' => [],
                    'only_domains' => [],
                ],
            ],
        ];

        $serviceManager = $this->getServiceManagerMock($config);

        $listener = $this->getMissingTranslationListenerMock();
        $listener->expects($this->once())->method('attach');

        $serviceManager
            ->expects($this->at(3))
            ->method('get')
            ->with('MyI18n\Listener\MissingTranslation')
            ->will($this->returnValue($listener));

        $translator = $translatorFactory->createService($serviceManager);
        $this->assertTrue($translator->isEventManagerEnabled());

    }

    public function getServiceManagerMock($config)
    {
        $serviceManager = $this->getMock('Zend\ServiceManager\ServiceManager');

        $serviceManager
            ->expects($this->at(1))
            ->method('get')
            ->with($this->matchesRegularExpression('/config/i'))
            ->will($this->returnValue($config));

        $serviceManager
            ->expects($this->at(2))
            ->method('get')
            ->with('MyI18n\Service\TranslationService')
            ->will($this->returnValue($this->getTranslatorServiceMock()));

        return $serviceManager;
    }

    public function getTranslatorServiceMock()
    {
        $entityManager = $this->getMock('Doctrine\ORM\EntityManager', [], [], '', false);

        $translatorService = $this->getMock('MyI18n\Service\TranslationService', [], [$entityManager]);

        $translatorService
            ->expects($this->once())
            ->method('getAllDomains')
            ->will($this->returnValue(['someTextDomain']));

        return $translatorService;
    }

    public function getMissingTranslationListenerMock()
    {
        $listener = $this->getMock('MyI18n\Listener\MissingTranslation', [], [], '', false);

        return $listener;
    }
}
