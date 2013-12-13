<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18nTest\TestAsset;

use MyI18n\DataMapper\TranslationMapperAwareInterface;
use MyI18n\DataMapper\TranslationMapperAwareTrait;
use MyI18n\Service\TranslationServiceTrait;
use MyI18n\Service\TranslationServiceInterface;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerAwareTrait;

class TranslationService implements
    TranslationServiceInterface,
    TranslationMapperAwareInterface,
    EventManagerAwareInterface
{
    use TranslationServiceTrait;
    use TranslationMapperAwareTrait;
    use EventManagerAwareTrait;
}
