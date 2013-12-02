<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18nTest\Entity;

use MyI18n\Entity\Locale;
use PHPUnit_Framework_TestCase;

class LocaleTest extends PHPUnit_Framework_TestCase
{
    public function testToString()
    {
        $locale = new Locale('it');

        $this->assertSame('it', (string) $locale);
    }
}
