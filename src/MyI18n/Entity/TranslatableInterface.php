<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\Entity;

interface TranslatableInterface
{
    public function getLocale();
    public function setLocale($locale);
}
