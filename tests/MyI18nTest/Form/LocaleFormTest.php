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
                [ 'code' => 'en', 'mode' => 'enable'],
                [ 'code' => 'en', 'mode' => 'enable', 'submit' => null],
                true
            ],
            [
                [ 'code' => 'IT', 'mode' => 'Enable'],
                [ 'code' => 'it', 'mode' => 'enable', 'submit' => null],
                true
            ],
            [
                [ 'code' => 'En', 'mode' => 'DISABLE'],
                [ 'code' => 'en', 'mode' => 'disable', 'submit' => null],
                true
            ],
            [
                [ 'code' => 'invalid', 'mode' => 'invalid'],
                [ 'code' => 'invalid', 'mode' => 'invalid', 'submit' => null],
                false
            ],
            [
                [ 'code' => 'it', 'mode' => 'invalid'],
                [ 'code' => 'it', 'mode' => 'invalid', 'submit' => null],
                false
            ],
        ];
    }
}
