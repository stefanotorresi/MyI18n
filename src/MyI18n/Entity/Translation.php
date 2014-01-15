<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Translatable\Entity\MappedSuperclass\AbstractTranslation;

/**
 * @ORM\Table(name="mi18n_translations", indexes={
 *      @ORM\Index(name="mi18n_translation_idx", columns={"locale", "object_class", "field", "foreign_key"})
 * })
 * @ORM\Entity(repositoryClass="MyI18n\DataMapper\TranslationMapper")
 */
class Translation extends AbstractTranslation
{
    /**
     * All required columns are mapped through inherited superclass
     */
}
