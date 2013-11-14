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

    public function __construct($code = null)
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $locale
     * @return $this
     */
    public function setCode($locale)
    {
        $this->code = $locale;

        return $this;
    }

    public function __toString()
    {
        return $this->code;
    }
}
