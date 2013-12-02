<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18nTest\Service;

use MyI18n\Entity\Locale;
use PHPUnit_Framework_TestCase;

/**
 * Class LocaleHelperTraitTest
 * @package MyI18nTest\Service
 *
 * @covers \MyI18n\Service\LocaleHelperTrait
 */
class LocaleHelperTraitTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var object $traitExhibitingObject
     */
    protected $traitExhibitingObject;

    public function setUp()
    {
        $this->traitExhibitingObject = $this->getObjectForTrait('MyI18n\Service\LocaleHelperTrait');
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

        $localeService = $this->getMockBuilder('MyI18n\Service\LocaleService')->disableOriginalConstructor()->getMock();
        $localeService->expects($this->atLeastOnce())
            ->method('getDefaultLocale')
            ->will($this->returnValue($locale));

        $this->traitExhibitingObject->setLocaleService($localeService);

        $this->assertSame($locale, $this->traitExhibitingObject->__invoke()->getDefault());
        $this->assertSame($locale->getCode(), $this->traitExhibitingObject->__invoke()->getDefault(false));
    }
}
