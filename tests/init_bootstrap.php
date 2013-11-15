<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18nTest;

error_reporting(E_ALL | E_STRICT);
chdir(dirname(__DIR__));

include './tests/'. __NAMESPACE__ . '/Bootstrap.php';

Bootstrap::init();
