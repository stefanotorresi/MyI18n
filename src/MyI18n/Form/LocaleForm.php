<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\Form;

use MyI18n\Controller\LocaleController;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Mvc\Router\RouteStackInterface;

class LocaleForm extends Form implements InputFilterProviderInterface
{
    /**
     * @var bool
     */
    protected $isUpdating = false;

    /**
     * {@inheritdoc}
     */
    public function __construct($name = 'locale-form', $options = array())
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
            'type' => 'hidden',
            'name' => 'mode',
            'attributes' => [
                'value' => LocaleController::MODE_ENABLE
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
            'mode' => [
                'required' => true,
                'filters' => [
                    ['name' => 'stringtolower'],
                ],
                'validators' => [
                    [
                        'name' => 'inarray',
                        'options' => [
                            'haystack' => [
                                LocaleController::MODE_ENABLE,
                                LocaleController::MODE_DISABLE
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
