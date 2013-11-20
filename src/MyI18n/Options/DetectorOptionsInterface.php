<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\Options;

interface DetectorOptionsInterface
{
    public function getSupportedLocales();
    public function setSupportedLocales($supportedLocales);
    public function getKeyName();
    public function setKeyName($keyName);
}
