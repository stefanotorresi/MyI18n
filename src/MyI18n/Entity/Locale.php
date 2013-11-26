<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\Entity;

use MyBase\Entity\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="m18n_locales")
 */
class Locale extends Entity
{
    /**
     * @var string
     * @ORM\Column(type="string", unique=true)
     */
    protected $code;

    /**
     * @var bool
     * @ORM\Column(type="boolean", unique=true, nullable=true)
     */
    protected $defaultLocale;

    /**
     * @param string|null $code
     * @param bool|null   $defaultLocale
     */
    public function __construct($code = null, $defaultLocale = null)
    {
        $this->setCode($code);
        $this->setDefaultLocale($defaultLocale);
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    public function __toString()
    {
        return $this->code;
    }

    /**
     * @return boolean
     */
    public function isDefaultLocale()
    {
        return $this->defaultLocale;
    }

    /**
     * @param boolean $flag
     * @return $this
     */
    public function setDefaultLocale($flag = true)
    {
        $flag = (bool) $flag;

        // we have a unique constraint so it's either true or null
        if (! $flag) {
            $flag = null;
        }

        $this->defaultLocale = $flag;

        return $this;
    }
}
