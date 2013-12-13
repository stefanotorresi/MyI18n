<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18nTest\View\Helper;

use MyI18n\Entity\Locale;
use MyI18n\View\Helper\LangTabs;
use PHPUnit_Framework_TestCase;

class LangTabsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var LangTabs $helper
     */
    protected $helper;

    /**
     * @var array $locales
     */
    protected $locales;

    public function setUp()
    {
        $this->locales = [
           new Locale('en'),
           new Locale('it', true),
        ];

        $localeMapper = $this->getMockBuilder('MyI18n\Mapper\LocaleMapper')->disableOriginalConstructor()->getMock();

        $this->helper = new LangTabs($localeMapper);
    }

    public function testHelper()
    {
        $tabPrefix = $this->helper->getDefaultOptions()['tab_id_prefix'];

        $this->helper->getLocaleMapper()
            ->expects($this->once())
            ->method('getAllWithDefaultFirst')
            ->will($this->returnValue(array_reverse($this->locales)));

        $markup = $this->helper->__invoke();

        $this->assertRegExp(
            "/<ul.*?>(<li.*?><a href=\"#{$tabPrefix}-[a-z]{2}\" data-toggle=\"tab\">.*?<\/a><\/li>)+<\/ul>/",
            $markup
        );
    }

    public function testReturnNullWhenNoLocalesAreFound()
    {
        $this->helper->getLocaleMapper()
            ->expects($this->once())
            ->method('getAllWithDefaultFirst')
            ->will($this->returnValue([]));

        $markup = $this->helper->__invoke();

        $this->assertEmpty($markup);
    }

    public function testDefaultFirstOptionOverride()
    {
        $this->helper->getLocaleMapper()
            ->expects($this->once())
            ->method('getAll')
            ->will($this->returnValue($this->locales));

        $this->helper->__invoke(['default_first' => false]);
    }
}
