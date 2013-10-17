<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18nTest\Service;

use MyI18n\Entity\Translation;
use MyI18n\Service\TranslationService;
use MyI18nTest\EntityManagerAwareFunctionalTestTrait;
use MyI18nTest\TestAsset;
use PHPUnit_Framework_TestCase as TestCase;
use Zend\Paginator\Paginator;

class TranslationServiceFunctionalTest extends TestCase
{
    use EntityManagerAwareFunctionalTestTrait;

    /**
     * @var TranslationService;
     */
    protected $translationService;

    public function setUp()
    {
        $this->translationService = new TranslationService($this->getFunctionalEntityManager());
        TestAsset\Translations::populateService($this->translationService);
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
            $this->assertEquals(TestAsset\Translations::getTranslations()[$i]->getMsgstr(), $msgstr);
            $this->assertEquals(TestAsset\Translations::getTranslations()[$i]->getMsgid(), $msgid);
            $i++;
        }
    }

    public function testFindTranslation()
    {
        foreach (TestAsset\Translations::getTranslations() as $translation) {/** @var Translation $translation */
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
        foreach (TestAsset\Translations::getTranslations() as $translation) {/** @var Translation $translation */
            $this->assertEquals(
                $translation,
                $this->translationService->findTranslation(
                    $translation->getId()
                )
            );
        }
    }

    public function testGetPagedTranslation()
    {
        /** @var Paginator $paginator */
        $paginator = $this->translationService->getPagedTranslations(1, 2);

        $this->assertInstanceOf('Zend\Paginator\Paginator', $paginator);

        $this->assertEquals(1, $paginator->getCurrentPageNumber());
        $this->assertCount(2, $paginator);
        $this->assertEquals(3, $paginator->getTotalItemCount());
    }
}
