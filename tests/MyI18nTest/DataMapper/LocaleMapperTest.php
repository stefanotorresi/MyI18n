<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18nTest\DataMapper;

use MyBase\DataMapper\MapperEvent;
use MyI18n\Entity\Locale;
use MyI18n\DataMapper\LocaleMapper;
use PHPUnit_Framework_TestCase;

class LocaleMapperTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var LocaleMapper;
     */
    protected $localeMapper;

    /**
     * @var \Doctrine\ORM\Persisters\BasicEntityPersister
     */
    protected $entityPersister;

    public function setUp()
    {
        $entityPersister = $this->getMock('\Doctrine\ORM\Persisters\BasicEntityPersister', [], [], '', false);

        $unitOfWork = $this->getMock('\Doctrine\ORM\UnitOfWork', [], [], '', false);
        $unitOfWork->expects($this->any())
            ->method('getEntityPersister')
            ->will($this->returnValue($entityPersister));

        $entityManager = $this->getMock('\Doctrine\ORM\EntityManager', [], [], '', false);
        $entityManager->expects($this->any())
            ->method('getUnitOfWork')
            ->will($this->returnValue($unitOfWork));

        $metadataClass = $this->getMock('\Doctrine\ORM\Mapping\ClassMetadata', [], [], '', false);

        $this->entityPersister = $entityPersister;
        $this->localeMapper = new LocaleMapper($entityManager, $metadataClass);
    }

    public function testDefaultLocaleSwitcherListener()
    {
        $oldDefault = new Locale('en', true);
        $newDefault = new Locale('it', true);

        $this->entityPersister->expects($this->once())
            ->method('load')
            ->will($this->returnValue($oldDefault));

        $this->localeMapper->getEntityManager()
            ->expects($this->atLeastOnce())
            ->method('flush')
            ->with($oldDefault);

        $event = new MapperEvent();
        $event->setEntity($newDefault);

        $this->localeMapper->defaultLocaleSwitcherListener($event);

        $this->assertFalse($oldDefault->isDefaultLocale());
    }

    public function testDefaultLocaleSwitcherListenerDoesNothingWhenSavingDefaultLocale()
    {
        $newDefault = $oldDefault = new Locale('en', true);

        $this->entityPersister->expects($this->once())
            ->method('load')
            ->will($this->returnValue($oldDefault));

        $this->localeMapper->getEntityManager()
            ->expects($this->never())
            ->method('flush');

        $event = new MapperEvent();
        $event->setEntity($newDefault);

        $this->localeMapper->defaultLocaleSwitcherListener($event);

        $this->assertTrue($oldDefault->isDefaultLocale());
    }

    public function testDefaultLocaleSwitcherListenerWhenNoDefaultIsYetAvailable()
    {
        $newLocale = new Locale('it');

        $event = new MapperEvent();
        $event->setEntity($newLocale);

        $this->localeMapper->defaultLocaleSwitcherListener($event);

        $this->assertTrue($newLocale->isDefaultLocale());
    }

    public function testEnsureDefaultLocaleListener()
    {
        $removedLocale = new Locale('it', true);
        $lastLocale = new Locale('en');

        $this->entityPersister
            ->expects($this->at(0))
            ->method('load')
            ->will($this->returnValue($lastLocale));

        $this->entityPersister
            ->expects($this->at(1))
            ->method('load')
            ->with(['defaultLocale' => true])
            ->will($this->returnValue(null));

        $this->localeMapper->getEntityManager()
            ->expects($this->atLeastOnce())
            ->method('persist')
            ->with($lastLocale);

        $event = new MapperEvent();
        $event->setEntity($removedLocale);

        $this->localeMapper->ensureDefaultLocaleListener($event);

        $this->assertTrue($lastLocale->isDefaultLocale());
    }

    public function testEnsureDefaultLocaleListenerDoesNothingWhenRemovingNonDefaultLocale()
    {
        $this->localeMapper->getEntityManager()
            ->expects($this->never())
            ->method('persist');

        $event = new MapperEvent();
        $event->setEntity(new Locale('it'));

        $this->localeMapper->ensureDefaultLocaleListener($event);
    }

    public function testEnsureDefaultLocaleListenerDoesNothingWhenThereAreNoLocalesLeft()
    {
        $this->localeMapper->getEntityManager()
            ->expects($this->never())
            ->method('persist');

        $event = new MapperEvent();
        $event->setEntity(new Locale('it', true));

        $this->localeMapper->ensureDefaultLocaleListener($event);
    }

    public function testMakeDefault()
    {
        $locale = new Locale('ru');

        $this->localeMapper->getEntityManager()
            ->expects($this->atLeastOnce())
            ->method('persist')
            ->with($locale);

        $this->localeMapper->makeDefault($locale);

        $this->assertTrue($locale->isDefaultLocale());
    }
}
