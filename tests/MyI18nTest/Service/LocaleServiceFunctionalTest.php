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
use Zend\Paginator\Paginator;

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

    /**
     * @dataProvider localesProvider
     * @param Locale $locale
     */
    public function testFind($locale)
    {
        $found = $this->localeService->find($locale->getId());

        $this->assertEquals($locale, $found);
    }

    /**
     * @dataProvider localesProvider
     * @param Locale $locale
     */
    public function testFindOneByCode($locale)
    {
        $found = $this->localeService->findOneByCode($locale->getCode());

        $this->assertEquals($locale, $found);
    }

    public function localesProvider()
    {
        $data = [];
        foreach (TestAsset\Locales::getLocales() as $locale) {
            $data[] = [$locale];
        };

        return $data;
    }
}
