<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18nTest\Service\Factory;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use MyI18n\Entity\Translation;
use MyI18n\Service\Factory\TranslatorFactory;
use MyI18nTest\Bootstrap;
use MyI18nTest\TestAsset\Translations;
use PHPUnit_Framework_TestCase as TestCase;
use Zend\I18n\Translator\Translator;
use Zend\Stdlib\SplPriorityQueue;

class TranslatorFactoryTest extends TestCase
{
    public function setUp()
    {
        /** @var EntityManager $entityManager */
        $entityManager = Bootstrap::getServiceManager()->get('Doctrine\ORM\EntityManager');

        $classes    = $entityManager->getMetadataFactory()->getAllMetadata();

        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->dropDatabase();
        $schemaTool->createSchema($classes);

        $translationService = Bootstrap::getServiceManager()->get('MyI18n\Service\TranslationService');

        Translations::populateService($translationService);
    }

    public function testTranslationsLoading()
    {
        /** @var Translator $translator */
        $translator = Bootstrap::getServiceManager()->get('translator');

        foreach (Translations::getTranslations() as $translation) { /** @var Translation $translation */
            $translator->setLocale($translation->getLocale()->getCode());
            $this->assertEquals(
                $translation->getMsgstr(),
                $translator->translate($translation->getMsgid(), $translation->getDomain())
            );
        }
    }

    public function testMissingTranslationListenerEnabler()
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

        $translator = $translatorFactory->createService($serviceManager);

        /** @var SplPriorityQueue $listeners */
        $listeners = $translator->getEventManager()->getListeners(Translator::EVENT_MISSING_TRANSLATION);

        $this->assertTrue($translator->isEventManagerEnabled());
        $this->assertCount(1, $listeners);
    }

    public function testMissingTranslationListenerDisabled()
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

        $translator = $translatorFactory->createService($serviceManager);

        /** @var SplPriorityQueue $listeners */
        $listeners = $translator->getEventManager()->getListeners(Translator::EVENT_MISSING_TRANSLATION);

        $this->assertFalse($translator->isEventManagerEnabled());
        $this->assertCount(0, $listeners);
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
        $localeService = $this->getMock('MyI18n\Service\LocaleService', [], [$entityManager]);

        $translatorService = $this->getMock('MyI18n\Service\TranslationService', [], [$entityManager, $localeService]);

        $translatorService
            ->expects($this->once())
            ->method('getAllDomains')
            ->will($this->returnValue([]));

        return $translatorService;
    }
}
