<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\Form;

use Zend\Form\Form;

class TranslationForm extends Form
{
    /**
     * @var bool
     */
    protected $isUpdating = false;

    /**
     * {@inheritdoc}
     */
    public function __construct($name = 'translation-form', $options = array())
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
            'name' => 'translationFormCSRF',
            'type' => 'csrf',
        ));

        $this->add([
            'name' => 'translation',
            'type' => __NAMESPACE__ . '\Fieldset\Translation'
        ]);

        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'options' => array(
                'label' => 'Add',
            ),
        ));

        $this->setBaseFieldset($this->get('translation'));
    }

    /**
     * @return boolean
     */
    public function isUpdating()
    {
        return $this->isUpdating;
    }
}
