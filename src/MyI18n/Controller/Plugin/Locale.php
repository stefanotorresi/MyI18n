<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\Controller\Plugin;

use MyI18n\Service;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class Locale extends AbstractPlugin implements Service\LocaleServiceAwareInterface
{
    use Service\LocaleHelperTrait;
}
