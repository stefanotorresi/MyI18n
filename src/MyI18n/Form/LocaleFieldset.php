<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\Form;

use MyI18n\Entity\Locale;
use Zend\Form\Fieldset;
use Doctrine\Common\Persistence\ObjectManager;

class LocaleFieldset extends Fieldset
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    public function __construct(ObjectManager $objectManager, $name = 'locale', $options = array())
    {
        parent::__construct($name, $options);

        $this->objectManager = $objectManager;
    }

    public function init()
    {
        $this->add(
            array(
                'name' => 'id',
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'options' => array(
                    'empty_option' => '',
                    'object_manager' => $this->objectManager,
                    'target_class'   => Locale::fqcn(),
                    'property'       => 'code',
                    'find_method'    => array(
                        'name'   => 'findBy',
                        'params' => array(
                            'criteria' => array(),
                            'orderBy'  => array('code' => 'ASC'),
                        ),
                    ),
                    'label' => 'Language',
                ),
                'attributes' => array(
                    'required' => true,
                ),
            )
        );
    }
}
