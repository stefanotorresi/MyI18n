<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18nTest\DataMapper;

use MyI18n\Entity\Locale;
use PHPUnit_Framework_TestCase;

/**
 * Class LocaleMapperPluginTraitTest
 * @package MyI18nTest\DataMapper
 *
 * @covers \MyI18n\DataMapper\LocaleMapperPluginTrait
 */
class LocaleMapperPluginTraitTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var object $traitExhibitingObject
     */
    protected $traitExhibitingObject;

    public function setUp()
    {
        $this->traitExhibitingObject = $this->getObjectForTrait('MyI18n\DataMapper\LocaleMapperPluginTrait');
    }

    public function testInvokeReturnsSelf()
    {
        $this->assertSame($this->traitExhibitingObject, $this->traitExhibitingObject->__invoke());
    }

    public function testGetCurrentLocale()
    {
        if (! extension_loaded('intl')) {
            $this->markTestSkipped('Intl extension not available');
        }

        $this->assertSame(\Locale::getDefault(), $this->traitExhibitingObject->__invoke()->getCurrent());
    }

    public function testGetDefaultLocale()
    {
        $locale = new Locale('en', true);

        $localeMapper = $this->getMockBuilder('MyI18n\DataMapper\LocaleMapper')->disableOriginalConstructor()->getMock();
        $localeMapper->expects($this->atLeastOnce())
            ->method('findDefaultLocale')
            ->will($this->returnValue($locale));

        $this->traitExhibitingObject->setLocaleMapper($localeMapper);

        $this->assertSame($locale, $this->traitExhibitingObject->__invoke()->getDefault());
        $this->assertSame($locale->getCode(), $this->traitExhibitingObject->__invoke()->getDefault(false));
    }
}
