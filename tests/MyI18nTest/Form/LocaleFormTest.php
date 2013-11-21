<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18nTest\Form;

use MyI18n\Form\LocaleForm;

class LocaleFormTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LocaleForm
     */
    protected $form;

    public function setUp()
    {
        $this->form = new LocaleForm();
    }

    /**
     * @dataProvider dataProvider
     * @param $data
     * @param $expectedData
     * @param $expectedValidity
     */
    public function testForm($data, $expectedData, $expectedValidity)
    {
        $this->form->setData($data);

        $isValid = $this->form->isValid();

        $this->assertSame($expectedValidity, $isValid);

        $validatedData = $this->form->getData();

        $this->assertSame($expectedData, $validatedData);;
    }

    public function dataProvider()
    {
        return [
            [
                [ 'code' => 'en', 'defaultLocale' => null, ],
                [ 'code' => 'en', 'defaultLocale' => false, 'submit' => null],
                true
            ],
            [
                [ 'code' => 'IT', 'defaultLocale' => 1, ],
                [ 'code' => 'it', 'defaultLocale' => true, 'submit' => null],
                true
            ],
            [
                [ 'code' => 'En', 'defaultLocale' => 'asdasd', ],
                [ 'code' => 'en', 'defaultLocale' => true, 'submit' => null],
                true
            ],
            [
                [ 'code' => 'invalid', 'defaultLocale' => 0, ],
                [ 'code' => 'invalid', 'defaultLocale' => false, 'submit' => null],
                false
            ],
        ];
    }
}
