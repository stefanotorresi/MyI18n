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
    public function __construct($name = 'form-translations', $options = array())
    {
        parent::__construct($name, $options);
    }

    public function init()
    {
        $this->add(array(
            'name' => 'formTranslationsCSRF',
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
                'label' => 'Submit',
            ),
        ));

        $this->setBaseFieldset($this->get('translation'));
    }
}
