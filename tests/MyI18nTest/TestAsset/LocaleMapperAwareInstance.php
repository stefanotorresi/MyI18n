<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18nTest\TestAsset;

use MyI18n\DataMapper\LocaleMapperAwareInterface;
use MyI18n\DataMapper\LocaleMapperAwareTrait;

class LocaleMapperAwareInstance implements LocaleMapperAwareInterface
{
    use LocaleMapperAwareTrait;
}
