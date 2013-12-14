<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18nTest\Navigation;

use Locale as IntlLocale;
use MyI18n\DataMapper\LocaleMapperInterface;
use MyI18n\Entity\Locale;
use MyI18n\Navigation\NavigationFactory;
use MyI18n\Options\ModuleOptions;
use MyI18n\Options\NavigationOptions;
use PHPUnit_Framework_TestCase as TestCase;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Zend\Navigation\Page\Mvc as MvcPage;
use Zend\ServiceManager\ServiceManager;

class NavigationFactoryTest extends TestCase
{
    /**
     * @var NavigationFactory
     */
    protected $navigationFactory;

    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @var LocaleMapperInterface
     */
    protected $localeMapper;

    /**
     * @var MvcEvent
     */
    protected $mvcEvent;

    /**
     * @var ModuleOptions
     */
    protected $options;

    public function setUp()
    {
        if (! extension_loaded('intl')) {
            $this->markTestSkipped('Intl extension not available');
        }

        $application    = $this->getMock('Zend\Mvc\Application', [], [], '', false);
        $router         = $this->getMock('Zend\Mvc\Router\RouteStackInterface', [], [], '', false);
        $localeMapper   = $this->getMock('MyI18n\DataMapper\LocaleMapperInterface', [], [], '', false);
        $serviceManager = new ServiceManager();
        $mvcEvent       = new MvcEvent();
        $options        = new ModuleOptions();

        $application->expects($this->any())
            ->method('getMvcEvent')
            ->will($this->returnValue($mvcEvent));

        $mvcEvent->setRouter($router);
        $mvcEvent->setRouteMatch(new RouteMatch([]));

        $serviceManager->setService('application', $application);
        $serviceManager->setService('MyI18n\DataMapper\LocaleMapper', $localeMapper);
        $serviceManager->setService('MyI18n\Options\ModuleOptions', $options);

        $this->navigationFactory = new NavigationFactory();
        $this->serviceManager   = $serviceManager;
        $this->localeMapper     = $localeMapper;
        $this->mvcEvent         = $mvcEvent;
        $this->options          = $options;
    }

    public function testBaseFunctionality()
    {
        $locales = [
            new Locale('it'),
            new Locale('en'),
        ];

        $this->localeMapper->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue($locales));

        $navigation = $this->navigationFactory->createService($this->serviceManager);

        $this->assertInstanceOf('Zend\Navigation\Navigation', $navigation);
        $pages = $navigation->getPages();
        $this->assertCount(2, $pages);

        $i = 0;
        foreach ($pages as $page) { /** @var MvcPage $page */
            $this->assertInstanceOf('Zend\Navigation\Page\Mvc', $page);

            $params = $page->getParams();
            $this->assertArrayHasKey('lang', $params);

            $this->assertSame($params['lang'], $locales[$i]->getCode());
            $fullLangName = ucfirst(IntlLocale::getDisplayLanguage($locales[$i], $locales[$i]));
            $this->assertSame($fullLangName, $page->getLabel());
            $this->assertSame($fullLangName, $page->getTitle());
            $this->assertSame('alternate', $page->getRel('alternate'));
            $this->assertSame($this->options->getNavigationOptions()->getSwitchRoute(), $page->getRoute());
            $this->assertSame(sprintf($this->options->getNavigationOptions()->getClassFormat(), $locales[$i]->getCode()), $page->getClass());
            $i++;
        }
    }

    public function testCurrentLocaleIsSetActive()
    {
        $locale = 'it';

        $this->localeMapper->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue([
                new Locale($locale),
            ]));

        IntlLocale::setDefault($locale);

        $navigation = $this->navigationFactory->createService($this->serviceManager);

        $page = $navigation->findOneBy('label', ucfirst(IntlLocale::getDisplayLanguage($locale, $locale)));
        $this->assertInstanceOf('Zend\Navigation\Page\Mvc', $page);
        $this->assertTrue($page->isActive());
    }

    public function testOptionLabelDisplayActiveFull()
    {
        $this->options->getNavigationOptions()->setLabelDisplay(NavigationOptions::LABEL_DISPLAY_ACTIVE_FULL);

        $locale = 'it';

        $this->localeMapper->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue([
                new Locale($locale),
                new Locale('en'),
            ]));

        IntlLocale::setDefault($locale);

        $navigation = $this->navigationFactory->createService($this->serviceManager);

        $itPage = $navigation->findOneBy('title', ucfirst(IntlLocale::getDisplayLanguage($locale, $locale)));
        $this->assertInstanceOf('Zend\Navigation\Page\Mvc', $itPage);
        $this->assertTrue($itPage->isActive());
        $this->assertSame(ucfirst(IntlLocale::getDisplayLanguage($locale, $locale)), $itPage->getLabel());

        $enPage = $navigation->findOneBy('title', ucfirst(IntlLocale::getDisplayLanguage('en', 'en')));
        $this->assertInstanceOf('Zend\Navigation\Page\Mvc', $enPage);
        $this->assertSame(strtoupper(IntlLocale::getPrimaryLanguage('en')), $enPage->getLabel());
    }

    public function testOptionLabelDisplayShort()
    {
        $this->options->getNavigationOptions()->setLabelDisplay(NavigationOptions::LABEL_DISPLAY_SHORT);

        $locales = [
            new Locale('it'),
            new Locale('en'),
        ];

        $this->localeMapper->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue($locales));

        $navigation = $this->navigationFactory->createService($this->serviceManager);

        $i = 0;
        foreach ($navigation->getPages() as $page) {
            $this->assertSame(strtoupper(IntlLocale::getPrimaryLanguage($locales[$i])), $page->getLabel());
            $i++;
        }
    }

    public function testOptionQueryString()
    {
        $this->options->getNavigationOptions()->setQueryString(true);

        $locale = new Locale('it');

        $this->localeMapper->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue([
                $locale,
            ]));

        $navigation = $this->navigationFactory->createService($this->serviceManager);

        $page = $navigation->findOneBy('label', ucfirst(IntlLocale::getDisplayLanguage($locale, $locale)));
        $this->assertInstanceOf('Zend\Navigation\Page\Uri', $page);
        $this->assertSame(
            sprintf(
                $this->options->getNavigationOptions()->getUriFormat(),
                $this->options->getKeyName(),
                $locale
            ),
            $page->getUri()
        );
    }
}
