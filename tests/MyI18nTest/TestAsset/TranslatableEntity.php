<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18nTest\TestAsset;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Translatable\Translatable;
use MyBase\Entity\Entity;

/**
 * @ORM\Table(name="translatable")
 * @ORM\Entity
 * @Gedmo\TranslationEntity(class="MyI18n\Entity\Translation")
 */
class TranslatableEntity extends Entity implements Translatable
{
    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text")
     */
    protected $text;

    /**
     * @Gedmo\Locale
     */
    protected $locale;

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param mixed $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }
}
