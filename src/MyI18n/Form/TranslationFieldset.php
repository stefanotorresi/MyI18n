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

        $this->add([
            'name' => 'id',
            'type' => 'hidden'
        ]);

        $this->add([
            'name' => 'domain',
            'type' => 'hidden'
        ]);

        $this->add([
            'name' => 'msgid',
            'type' => 'hidden'
        ]);

        $this->add([
            'name' => 'msgstr',
            'type' => 'hidden'
        ]);
    }

}
