<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18nTest\TestAsset;

use MyI18n\Service\LocaleServiceAwareInterface;
use MyI18n\Service\LocaleServiceAwareTrait;

class LocaleServiceAwareInstance implements LocaleServiceAwareInterface
{
    use LocaleServiceAwareTrait;
}
