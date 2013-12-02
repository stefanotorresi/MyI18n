<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18nTest\Service;

use MyI18n\Entity\Locale;
use MyI18n\Service\LocaleService;
use PHPUnit_Framework_TestCase;
use Zend\EventManager\Event;

class LocaleServiceTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var LocaleService;
     */
    protected $localeService;

    /**
     * @var \Doctrine\ORM\EntityRepository $localeRepository
     */
    protected $localeRepository;

    public function setUp()
    {
        $entityManager = $this->getMockBuilder('\Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();

        $localeRepository = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
            ->disableOriginalConstructor()->getMock();

        $entityManager->expects($this->any())
            ->method('getRepository')
            ->with('MyI18n\Entity\Locale')
            ->will($this->returnValue($localeRepository));

        $this->localeService = new LocaleService($entityManager);
        $this->localeRepository = $localeRepository;
    }

    public function testDefaultLocaleSwitcherListener()
    {
        $oldDefault = new Locale('en', true);
        $newDefault = new Locale('it', true);

        $this->localeRepository
            ->expects($this->atLeastOnce())
            ->method('findOneBy')
            ->with(['defaultLocale' => true])
            ->will($this->returnValue($oldDefault));

        $this->localeService->getEntityManager()
            ->expects($this->atLeastOnce())
            ->method('flush')
            ->with($oldDefault);

        $event = new Event();
        $event->setParam('entity', $newDefault);

        $this->localeService->defaultLocaleSwitcherListener($event);

        $this->assertFalse($oldDefault->isDefaultLocale());
    }

    public function testEnsureDefaultLocaleListener()
    {
        $removedLocale = new Locale('it', true);
        $lastLocale = new Locale('en');

        $this->localeRepository
            ->expects($this->at(0))
            ->method('findOneBy')
            ->will($this->returnValue($lastLocale));

        $this->localeRepository
            ->expects($this->at(1))
            ->method('findOneBy')
            ->with(['defaultLocale' => true])
            ->will($this->returnValue(null));

        $this->localeService->getEntityManager()
            ->expects($this->atLeastOnce())
            ->method('persist')
            ->with($lastLocale);

        $event = new Event();
        $event->setParam('entity', $removedLocale);

        $this->localeService->ensureDefaultLocaleListener($event);

        $this->assertTrue($lastLocale->isDefaultLocale());
    }

    public function testEnsureDefaultLocaleListenerDoesNothingWhenRemovingNonDefaultLocale()
    {
        $this->localeService->getEntityManager()
            ->expects($this->never())
            ->method('persist');

        $event = new Event();
        $event->setParam('entity', new Locale('it'));

        $this->localeService->ensureDefaultLocaleListener($event);
    }

    public function testEnsureDefaultLocaleListenerDoesNothingWhenThereAreNoLocalesLeft()
    {
        $this->localeRepository
            ->expects($this->atLeastOnce())
            ->method('findOneBy')
            ->will($this->returnValue(null));

        $this->localeService->getEntityManager()
            ->expects($this->never())
            ->method('persist');

        $event = new Event();
        $event->setParam('entity', new Locale('it', true));

        $this->localeService->ensureDefaultLocaleListener($event);
    }

    public function testMakeDefault()
    {
        $locale = new Locale('ru');

        $this->localeService->getEntityManager()
            ->expects($this->atLeastOnce())
            ->method('persist')
            ->with($locale);

        $this->localeService->makeDefault($locale);

        $this->assertTrue($locale->isDefaultLocale());
    }
}
