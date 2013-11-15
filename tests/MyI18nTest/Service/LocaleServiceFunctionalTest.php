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
}
