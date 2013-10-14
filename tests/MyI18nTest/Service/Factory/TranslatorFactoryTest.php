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
use MyI18nTest\Bootstrap;
use MyI18nTest\TestAsset\Translations;
use PHPUnit_Framework_TestCase as TestCase;
use Zend\I18n\Translator\Translator;

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
            $translator->setLocale($translation->getLocale());
            $this->assertEquals(
                $translation->getMsgstr(),
                $translator->translate($translation->getMsgid(), $translation->getDomain())
            );
        }
    }
}
