<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18nTest;

use Gedmo\Translatable\TranslatableListener;
use MyI18nTest\TestAsset\TranslatableEntity;

class TranslatableFunctionalTest extends \PHPUnit_Framework_TestCase
{
    use EntityManagerAwareFunctionalTestTrait;

    public function testIntegration()
    {
        $em = $this->getNewEntityManager();
        $em->getEventManager()->addEventSubscriber(new TranslatableListener);

        $translatable = new TranslatableEntity();
        $translatable->setId(1);
        $translatable->setText('text');
        $em->persist($translatable);
        $em->flush();

        $entity = $em->find(TranslatableEntity::fqcn(), 1);
        $this->assertEquals($translatable, $entity);

        $repository = $em->getRepository('MyI18n\\Entity\\Translation');
        $repository->translate($entity, 'text', 'it', 'testo');
        $em->flush();

        $translations = $repository->findTranslations($entity);
        $this->assertCount(1, $translations);

        $entity->setLocale('it');
        $em->refresh($entity);
        $this->assertEquals('testo', $entity->getText());

        $entity->setLocale('en_US');
        $em->refresh($entity);
        $this->assertEquals('text', $entity->getText());
    }
}
