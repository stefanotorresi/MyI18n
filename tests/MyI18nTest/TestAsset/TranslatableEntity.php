<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18nTest\TestAsset;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use MyBase\Entity\Entity;
use MyI18n\Entity\TranslatableInterface;
use MyI18n\Entity\TranslatableTrait;

/**
 * @ORM\Table(name="translatable")
 * @ORM\Entity
 * @Gedmo\TranslationEntity(class="MyI18n\Entity\Translation")
 */
class TranslatableEntity extends Entity implements TranslatableInterface
{
    use TranslatableTrait;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text")
     */
    protected $text;

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
}
