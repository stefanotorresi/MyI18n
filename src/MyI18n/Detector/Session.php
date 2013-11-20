<?php

namespace MyI18n\Detector;

use Zend\Mvc\MvcEvent;
use Zend\Session\Container as SessionContainer;

class Session extends AbstractDetector
    implements PersistCapableInterface, SessionAwareInterface
{
    /**
     * @var SessionContainer
     */
    protected $session;

    /**
     *
     * @param  MvcEvent $e
     * @return string
     */
    public function getLocale(MvcEvent $e)
    {
        $param = $this->getSession()->{$this->getOptions()->getKeyName()};

        if ($param) {
            return $this->lookup($param);
        }
    }

    public function persist($locale)
    {
        $this->getSession()->{$this->getOptions()->getKeyName()} = $locale;
    }

    /**
     * @return SessionContainer
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @param SessionContainer $session
     */
    public function setSession(SessionContainer $session)
    {
        $this->session = $session;
    }
}
