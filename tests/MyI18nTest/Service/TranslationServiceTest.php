<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18nTest\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use MyI18n\Entity\Translation;
use MyI18n\Service\TranslationService;
use MyI18nTest\Bootstrap;
use MyI18nTest\TestAsset\Translations;
use PHPUnit_Framework_TestCase as TestCase;
use Zend\EventManager\Event;

class TranslationServiceTest extends TestCase
{
    /**
     * @var TranslationService;
     */
    protected $translationService;

    public function setUp()
    {
        $this->translationService = Bootstrap::getServiceManager()->get('MyI18n\Service\TranslationService');

        /** @var EntityManager $entityManager */
        $entityManager = Bootstrap::getServiceManager()->get('Doctrine\ORM\EntityManager');
        $classes    = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->dropDatabase();
        $schemaTool->createSchema($classes);
        Translations::populateService($this->translationService);
    }

    public function testGetAllDomains()
    {
        $domains = $this->translationService->getAllDomains();

        $this->assertContains('test', $domains);
        $this->assertContains('test-2', $domains);
        $this->assertCount(2, $domains);
    }

    public function testLoad()
    {
        $testDomain = $this->translationService->load('it', 'test');

        $this->assertInstanceOf('Zend\I18n\Translator\TextDomain', $testDomain);

        $testDomain2 = $this->translationService->load('it', 'test-2');

        $this->assertCount(2, $testDomain);
        $this->assertCount(1, $testDomain2);

        $translations = $testDomain->merge($testDomain2);

        $i = 0;
        foreach ($translations as $msgid => $msgstr) {
            $this->assertEquals(Translations::getTranslations()[$i]->getMsgstr(), $msgstr);
            $this->assertEquals(Translations::getTranslations()[$i]->getMsgid(), $msgid);
            $i++;
        }
    }

    public function testMissingTranslationListener()
    {
        $event = new Event;
        $event->setParam('message', 'foo');
        $event->setParam('text_domain', 'bar');

        $this->translationService->missingTranslationListener($event);

        $translation = $this->translationService->findTranslation('foo', 'bar');

        $this->assertInstanceOf('MyI18n\Entity\Translation', $translation);
    }

    public function testFindTranslation()
    {
        foreach (Translations::getTranslations() as $translation) {/** @var Translation $translation */
            $this->assertEquals(
                $translation,
                $this->translationService->findTranslation(
                    $translation->getMsgid(),
                    $translation->getDomain()
                )
            );
        }
    }

    public function testFindTranslationByIdOnly()
    {
        foreach (Translations::getTranslations() as $translation) {/** @var Translation $translation */
            $this->assertEquals(
                $translation,
                $this->translationService->findTranslation(
                    $translation->getId()
                )
            );
        }
    }
}
