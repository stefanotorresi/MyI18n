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

    public function testGetDefaultLocale()
    {
        $default = array_filter(TestAsset\Locales::getLocales(), function(Locale $locale){
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

    public function testDefaultLocaleUniqueConstraint()
    {
        $locale = new Locale('ru');
        $locale->setDefaultLocale();
        try {
            $this->localeService->save($locale);
            $this->fail(sprintf(
                'More than one Locale with field \'defaultLocale=true\' were successfully saved'
            ));
        } catch (\Doctrine\DBAL\DBALException $e) {
            $this->assertContains('defaultLocale is not unique', $e->getMessage());
        }
    }

    public function testGetAllCodesAsArray()
    {
        $this->assertSame(['de', 'en', 'it'], $this->localeService->getAllCodesAsArray());
    }
}
