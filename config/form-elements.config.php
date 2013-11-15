<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n;

use Zend\Form\FormElementManager;

return [
    'factories' => [
        'MyI18n\Form\LocaleFieldset' => function(FormElementManager $formElementManager) {
            $serviceManager = $formElementManager->getServiceLocator();
            $objectManager = $serviceManager->get('Doctrine\ORM\EntityManager');
            $hydrator = $serviceManager->get('HydratorManager')->get('DoctrineModule\Stdlib\Hydrator\DoctrineObject');

            $localeFieldset = new Form\LocaleFieldset($objectManager);
            $localeFieldset->setHydrator($hydrator)->setObject(new Entity\Locale());

            return $localeFieldset;
        },
    ],
];
