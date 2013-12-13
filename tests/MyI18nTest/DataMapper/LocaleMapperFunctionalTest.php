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
use MyI18nTest\Bootstrap;
use MyI18nTest\TestAsset;
use PHPUnit_Framework_TestCase as TestCase;
use Zend\EventManager\Event;

class LocaleMapperFunctionalTest extends TestCase
{
    /**
     * @var LocaleMapper;
     */
    protected $localeMapper;

    public function setUp()
    {
        $sm = Bootstrap::getServiceManager();
        $em = Bootstrap::getEntityManager($sm);
        $this->localeMapper = $em->getRepository(Locale::fqcn());
        TestAsset\Locales::populateMapper($this->localeMapper);
    }

    public function testFind()
    {
        foreach (TestAsset\Locales::getLocales() as $locale) {
            $found = $this->localeMapper->find($locale->getId());
            $this->assertEquals($locale, $found);
        };
    }

    public function testFindOneByCode()
    {
        foreach (TestAsset\Locales::getLocales() as $locale) {
            $found = $this->localeMapper->findOneByCode($locale->getCode());
            $this->assertEquals($locale, $found);
        };
    }

    public function testFindAll()
    {
        $this->assertEquals(TestAsset\Locales::getLocales(), $this->localeMapper->findAll());
    }

    public function testFindDefaultLocale()
    {
        $default = array_filter(TestAsset\Locales::getLocales(), function (Locale $locale) {
            return $locale->isDefaultLocale();
        });
        $default = array_pop($default);

        $this->assertEquals($default, $this->localeMapper->findDefaultLocale());
    }

    public function testCodeUniqueConstraint()
    {
        $nonUniqueCode = 'it';
        $locale = new Locale($nonUniqueCode);
        try {
            $this->localeMapper->save($locale);
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

        $default = $this->localeMapper->findDefaultLocale();

        $this->assertTrue($default->isDefaultLocale());

        $this->localeMapper->save($locale);

        $this->assertFalse($default->isDefaultLocale());
    }

    public function testGetAllCodesAsArray()
    {
        $this->assertSame(['de', 'en', 'it'], $this->localeMapper->getAllCodesAsArray());
    }

    public function testFindAllWithDefaultFirst()
    {
        $locales = $this->localeMapper->findAllWithDefaultFirst();
        $default = $this->localeMapper->findDefaultLocale();
        $first = $locales[0];
        $this->assertTrue($first->isDefaultLocale());
        $this->assertSame($default, $first);
    }

    public function testMakeDefault()
    {
        $locale = new Locale('ru');

        $this->localeMapper->makeDefault($locale);

        $this->assertTrue($locale->isDefaultLocale());
        $this->assertSame($locale, $this->localeMapper->findDefaultLocale());
    }

    public function testFindLastById()
    {
        $locale = $this->localeMapper->findLastById();
        $locales = TestAsset\Locales::getLocales();

        $this->assertEquals(end($locales), $locale);
    }

    public function testEnsureDefaultLocaleListener()
    {
        $defaultLocale = $this->localeMapper->findDefaultLocale();
        $this->localeMapper->remove($defaultLocale);

        $this->assertNotNull($this->localeMapper->findDefaultLocale());
    }

    public function testEnsureDefaultLocaleListenerDoesNothingWhenRemovingNonDefaultLocale()
    {
        $default = $this->localeMapper->findDefaultLocale();
        $this->assertNotNull($default);

        $event = new MapperEvent();
        $event->setEntity(new Locale('ru'));
        $this->localeMapper->ensureDefaultLocaleListener($event);

        $this->assertSame($default, $this->localeMapper->findDefaultLocale());
    }
}
