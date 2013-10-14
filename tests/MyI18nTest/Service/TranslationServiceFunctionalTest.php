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
use PHPUnit_Framework_TestCase as TestCase;

class TranslationServiceFunctionalTest extends TestCase
{
    /**
     * @var TranslationService;
     */
    protected $translationService;

    /**
     * @var array
     */
    protected $translations;

    public function setUp()
    {
        /** @var EntityManager $entityManager */
        $entityManager = Bootstrap::getServiceManager()->get('Doctrine\ORM\EntityManager');

        $classes    = $entityManager->getMetadataFactory()->getAllMetadata();

        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->dropDatabase();
        $schemaTool->createSchema($classes);

        $this->translationService = new TranslationService($entityManager);
    }

    public function testGetAllDomains()
    {
        $this->populateService();

        $domains = $this->translationService->getAllDomains();

        $this->assertContains('test', $domains);
        $this->assertContains('test-2', $domains);
        $this->assertCount(2, $domains);
    }

    public function testLoad()
    {
        $this->populateService();

        $testDomain = $this->translationService->load('it', 'test');

        $this->assertInstanceOf('Zend\I18n\Translator\TextDomain', $testDomain);

        $testDomain2 = $this->translationService->load('it', 'test-2');

        $this->assertCount(2, $testDomain);
        $this->assertCount(1, $testDomain2);

        $translations = $testDomain->merge($testDomain2);

        $i = 0;
        foreach ($translations as $msgid => $msgstr) {
            $this->assertEquals($this->getTranslations()[$i]->getMsgstr(), $msgstr);
            $this->assertEquals($this->getTranslations()[$i]->getMsgid(), $msgid);
            $i++;
        }
    }

    protected function getTranslations()
    {
        if (! $this->translations) {
            $translation = new Translation();
            $translation->setDomain('test')->setLocale('it')->setMsgid('foo')->setMsgstr('bar');

            $translation1 = new Translation();
            $translation1->setDomain('test')->setLocale('it')->setMsgid('hello')->setMsgstr('ciao');

            $translation2 = new Translation();
            $translation2->setDomain('test-2')->setLocale('it')->setMsgid('bye')->setMsgstr('derci');

            $this->translations = [
                $translation,
                $translation1,
                $translation2,
            ];
        }

        return $this->translations;
    }

    protected function populateService()
    {
        foreach ($this->getTranslations() as $t) {
            $this->translationService->save($t);
        }
    }
}
