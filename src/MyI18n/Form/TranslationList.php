<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\Form;

use Zend\Form\Form;

class TranslationList extends Form
{
    public function __construct($name = 'form-translations', $options = array())
    {
        parent::__construct($name, $options);

        $this->add([
            'name' => 'translations',
            'type' => 'collection',
            'options' => array(
                'label' => 'Translations',
                'allow_add' => true,
                'allow_remove' => false,
                'create_new_objects' => true,
                'target_element' => array(
                    'type' => 'TranslationFieldset'
                ),
            ),
        ]);

        $this->add(array(
            'name' => 'formTranslationsCSRF',
            'type' => 'csrf',
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'options' => array(
                'label' => 'Submit',
            ),
        ));
    }
}
