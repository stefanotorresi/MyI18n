<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18nTest\TestAsset;

use MyBase\Service\EntityManagerAwareTrait;
use MyI18n\Service\TranslationServiceTrait;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerAwareTrait;

class TranslationService implements EventManagerAwareInterface
{
    use TranslationServiceTrait;
    use EventManagerAwareTrait;
    use EntityManagerAwareTrait;
}
