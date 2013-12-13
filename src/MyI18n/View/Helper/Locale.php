<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\View\Helper;

use MyI18n\DataMapper;
use Zend\View\Helper\AbstractHelper;

class Locale extends AbstractHelper implements DataMapper\LocaleMapperAwareInterface
{
    use DataMapper\LocaleMapperPluginTrait;
}
