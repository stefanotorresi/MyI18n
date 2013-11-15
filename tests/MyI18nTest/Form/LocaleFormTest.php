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
        $this->form->init();
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
                [ 'code' => 'en', 'enable' => '1'],
                [ 'code' => 'en', 'enable' => true, 'submit' => null],
                true
            ],
            [
                [ 'code' => 'IT', 'enable' => '0'],
                [ 'code' => 'it', 'enable' => false, 'submit' => null],
                true
            ],
            [
                [ 'code' => 'En', 'enable' => 0],
                [ 'code' => 'en', 'enable' => false, 'submit' => null],
                true
            ],
            [
                [ 'code' => 'invalid', 'enable' => 'asdasd'],
                [ 'code' => 'invalid', 'enable' => true, 'submit' => null],
                false
            ],
        ];
    }
}
