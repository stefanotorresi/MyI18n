<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\Service\Factory;

use MyI18n\Form\TranslationForm;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TranslationFormFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return TranslationForm
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $form = new TranslationForm();
        $form->getFormFactory()->setFormElementManager($serviceLocator->get('FormElementManager'));
        $form->init();

        return $form;
    }
}
