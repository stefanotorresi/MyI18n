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
    public function __construct($name = 'locale-form', $options = array())
    {
        parent::__construct($name, $options);
    }

    /**
     * this method is called manually by the factory,
     * after FormElementManager has been injected into the element factory
     */
    public function init()
    {
        $this->add(array(
            'name' => 'code',
            'type' => 'MyI18n\Form\LanguageSelect'
        ));

        $this->add(array(
            'name' => 'enable',
            'type' => 'radio',
            'options' => [
                'value_options' => [
                    '1' => 'Enable',
                    '0' => 'Disable',
                ],
            ],
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'options' => array(
                'label' => 'Submit',
            ),
        ));
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return array(
            'code' => array(
                'filters' => array(
                    array('name' => 'stringtolower'),
                ),
            ),
            'enable' => array(
                'required' => false,
                'filters' => array(
                    array('name' => 'boolean'),
                ),
            ),
        );
    }
}
