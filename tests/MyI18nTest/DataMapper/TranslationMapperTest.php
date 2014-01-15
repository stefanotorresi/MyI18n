<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18nTest\DataMapper;

use Doctrine\ORM\EntityManager;
use MyI18n\DataMapper\TranslationMapper;
use PHPUnit_Framework_TestCase;

class TranslationMapperTest extends PHPUnit_Framework_TestCase
{
    /** @var TranslationMapper */
    protected $translationMapper;

    /** @var EntityManager */
    protected $entityManager;

    public function setUp()
    {
        $this->entityManager = $this->getMock('\Doctrine\ORM\EntityManager', [], [], '', false);

        $classMetadata = $this->getMock('\Doctrine\ORM\Mapping\ClassMetadata', [], [], '', false);
        $classMetadata->expects($this->any())
                      ->method('getReflectionClass')
                      ->will($this->returnValue(new \ReflectionClass('MyI18n\Entity\Translation')));

        $this->translationMapper = new TranslationMapper($this->entityManager, $classMetadata);
    }

    public function testRefresh()
    {
        $entity = $this->getMock('MyBase\Entity\Entity');
        $this->entityManager->expects($this->atLeastOnce())
                            ->method('refresh')
                            ->with($entity);

        $this->translationMapper->refresh($entity);
    }
}
