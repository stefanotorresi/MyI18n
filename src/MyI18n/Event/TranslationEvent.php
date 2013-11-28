<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\Event;

use MyI18n\Entity\TranslatableInterface;
use Zend\EventManager\Event;

class TranslationEvent extends Event
{
    const EVENT_TRANSLATE_PRE = 'translate.pre';
    const EVENT_TRANSLATE_POST = 'translate.post';

    /**
     * @var TranslatableInterface $entity
     */
    protected $entity;

    /**
     * @var array $translations
     */
    protected $translations;

    /**
     * @return TranslatableInterface
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param TranslatableInterface $entity
     * @return $this
     */
    public function setEntity(TranslatableInterface $entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Translations array is passed thru by reference to allow modification in listeners
     *
     * @return array
     */
    public function &getTranslations()
    {
        return $this->translations;
    }

    /**
     * Translations array is passed thru by reference to allow modification in listeners
     *
     * @param array $translations
     * @return $this
     */
    public function setTranslations(array &$translations)
    {
        $this->translations = &$translations;

        return $this;
    }

}
