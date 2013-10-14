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
     * @var string
     * @ORM\Column(type="string")
     */
    protected $locale;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    protected $msgid;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    protected $msgstr;

    /**
     * @param string $locale
     * @return $this
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
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
    public function getMsgid()
    {
        return $this->msgid;
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

    /**
     * @return string
     */
    public function getMsgstr()
    {
        return $this->msgstr;
    }
}
