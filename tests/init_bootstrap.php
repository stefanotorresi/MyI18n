<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

error_reporting(E_ALL | E_STRICT);
chdir(__DIR__);

include __DIR__ . '/MyI18nTest/Bootstrap.php';

MyI18nTest\Bootstrap::init();
