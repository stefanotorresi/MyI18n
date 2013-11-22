<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18nTest\Service;

use MyI18n\Entity\Locale;
use MyI18n\Service\LocaleService;
use MyI18nTest\EntityManagerAwareFunctionalTestTrait;
use MyI18nTest\TestAsset;
use PHPUnit_Framework_TestCase as TestCase;
use Zend\EventManager\Event;

class LocaleServiceFunctionalTest extends TestCase
{
    use EntityManagerAwareFunctionalTestTrait;

    /**
     * @var LocaleService;
     */
    protected $localeService;

    public function setUp()
    {
        $this->localeService = new LocaleService($this->getNewEntityManager());
        TestAsset\Locales::populateService($this->localeService);
    }

    public function testFind()
    {
        foreach (TestAsset\Locales::getLocales() as $locale) {
            $found = $this->localeService->find($locale->getId());
            $this->assertEquals($locale, $found);
        };
    }

    public function testFindOneByCode()
    {
        foreach (TestAsset\Locales::getLocales() as $locale) {
            $found = $this->localeService->findOneByCode($locale->getCode());
            $this->assertEquals($locale, $found);
        };
    }

    public function testGetAll()
    {
        $this->assertEquals(TestAsset\Locales::getLocales(), $this->localeService->getAll());
    }

    public function testGetDefaultLocale()
    {
        $default = array_filter(TestAsset\Locales::getLocales(), function (Locale $locale) {
            return $locale->isDefaultLocale();
        });
        $default = array_pop($default);

        $this->assertEquals($default, $this->localeService->getDefaultLocale());
    }

    public function testCodeUniqueConstraint()
    {
        $nonUniqueCode = 'it';
        $locale = new Locale($nonUniqueCode);
        try {
            $this->localeService->save($locale);
            $this->fail(sprintf(
                'A Locale with non unique field \'code=%s\' was successfully saved',
                $nonUniqueCode
            ));
        } catch (\Doctrine\DBAL\DBALException $e) {
            $this->assertContains('code is not unique', $e->getMessage());
        }
    }

    public function testDefaultLocaleUniqueConstraintIsAutomaticallyHandled()
    {
        $locale = new Locale('ru');
        $locale->setDefaultLocale();

        $default = $this->localeService->getDefaultLocale();

        $this->assertTrue($default->isDefaultLocale());

        $this->localeService->save($locale);

        $this->assertNull($default->isDefaultLocale());
    }

    public function testGetAllCodesAsArray()
    {
        $this->assertSame(['de', 'en', 'it'], $this->localeService->getAllCodesAsArray());
    }

    public function testGetAllWithDefaultFirst()
    {
        $locales = $this->localeService->getAllWithDefaultFirst();
        $default = $this->localeService->getDefaultLocale();
        $first = $locales[0];
        $this->assertTrue($first->isDefaultLocale());
        $this->assertSame($default, $first);
    }

    public function testMakeDefault()
    {
        $locale = new Locale('ru');

        $this->localeService->makeDefault($locale);

        $this->assertTrue($locale->isDefaultLocale());
        $this->assertSame($locale, $this->localeService->getDefaultLocale());
    }

    public function testGetLastById()
    {
        $locale = $this->localeService->getLastById();
        $locales = TestAsset\Locales::getLocales();

        $this->assertEquals(end($locales), $locale);
    }

    public function testEnsureDefaultLocaleListener()
    {
        $defaultLocale = $this->localeService->getDefaultLocale();
        $this->localeService->remove($defaultLocale);

        $this->assertNotNull($this->localeService->getDefaultLocale());
    }

    public function testEnsureDefaultLocaleListenerDoesNothingWhenRemovingNonDefaultLocale()
    {
        $default = $this->localeService->getDefaultLocale();
        $this->assertNotNull($default);

        $event = new Event();
        $event->setParam('entity', new Locale('ru'));
        $this->localeService->ensureDefaultLocaleListener($event);

        $this->assertSame($default, $this->localeService->getDefaultLocale());
    }
}
