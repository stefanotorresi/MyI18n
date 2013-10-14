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
 * @ORM\Table(name="m18n_translations")
 */
class Translation extends Entity
{
    /**
     * @var Locale
     * @ORM\ManyToOne(targetEntity="Locale", cascade={"persist"})
     */
    protected $locale;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $domain;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    protected $msgid;

    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    protected $msgstr;

    /**
     * @return Locale
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param Locale $locale
     * @return $this
     */
    public function setLocale(Locale $locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param string $domain
     * @return $this
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * @return string
     */
    public function getMsgid()
    {
        return $this->msgid;
    }

    /**
     * @param string $msgid
     * @return $this
     */
    public function setMsgid($msgid)
    {
        $this->msgid = $msgid;

        return $this;
    }

    /**
     * @return string
     */
    public function getMsgstr()
    {
        return $this->msgstr;
    }

    /**
     * @param string $msgstr
     * @return $this
     */
    public function setMsgstr($msgstr)
    {
        $this->msgstr = $msgstr;

        return $this;
    }
}
