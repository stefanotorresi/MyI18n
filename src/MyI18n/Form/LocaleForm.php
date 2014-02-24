<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

class LocaleForm extends Form implements InputFilterProviderInterface
{
    /**
     * @var bool
     */
    protected $isUpdating = false;

    /**
     * {@inheritdoc}
     */
    public function __construct($name = 'locale-form', $options = [])
    {
        parent::__construct($name, $options);

        $this->add([
            'name' => 'code',
            'type' => 'MyI18n\Form\LanguageSelect',
            'options' => [
                'label' => 'Select a language to enable'
            ],
        ]);

        $this->add([
            'name' => 'defaultLocale',
            'type' => 'checkbox',
            'options' => [
                'label' => 'Make default'
            ],
        ]);

        $this->add([
            'name' => 'submit',
            'type' => 'submit',
            'options' => [
                'label' => 'Enable',
            ],
        ]);
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            'code' => [
                'filters' => [
                    ['name' => 'stringtolower'],
                ],
            ],
            'defaultLocale' => [
                'required' => false,
                'filters' => [
                    ['name' => 'boolean'],
                ],
            ],
        ];
    }
}
