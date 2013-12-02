<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\Detector;

use Zend\Session\Container;

interface SessionAwareInterface
{
    public function getSession();
    public function setSession(Container $session);
}
