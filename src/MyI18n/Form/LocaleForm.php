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
            'type' => 'MyBase\Form\Element\CountrySelect'
        ));

        $this->add(array(
            'name' => 'enable',
            'type' => 'submit',
            'options' => array(
                'label' => 'Enable',
            ),
        ));

        $this->add(array(
            'name' => 'disable',
            'type' => 'submit',
            'options' => array(
                'label' => 'Disable',
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
        );
    }
}
