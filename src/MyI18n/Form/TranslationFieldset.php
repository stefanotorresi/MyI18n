<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\Form;

use Zend\Form\Fieldset;

class TranslationFieldset extends Fieldset
{
    public function __construct($name = 'translation', $options = array())
    {
        parent::__construct($name, $options);
    }

    public function init()
    {
        $this->add([
            'name' => 'id',
            'type' => 'hidden'
        ]);

        $this->add([
            'name' => 'msgid',
            'type' => 'textarea',
            'options' => [
                'label' => 'Text',
            ]
        ]);

        $this->add([
            'name' => 'msgstr',
            'type' => 'textarea',
            'options' => [
                'label' => 'Translation',
            ],
        ]);

        $this->add([
            'name' => 'domain',
            'type' => 'text',
            'options' => [
                'label' => 'Domain',
            ],
            'attributes' => [
                'value' => 'default',
            ],
        ]);

        $this->add([
            'name' => 'locale',
            'type' => __NAMESPACE__ . '\Fieldset\Locale',
            'options' => [
                'label' => 'Locale',
            ]
        ]);
    }
}
