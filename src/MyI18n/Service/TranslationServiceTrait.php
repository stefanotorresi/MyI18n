<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\Service;

use MyI18n\Entity\TranslatableInterface;
use MyI18n\Event\TranslationEvent;
use MyI18n\DataMapper\TranslationMapperInterface;
use Zend\EventManager\EventManagerAwareInterface;

trait TranslationServiceTrait
{
    /**
     * @param  TranslatableInterface $entity
     * @param  array                 $translations
     * @return TranslatableInterface
     */
    public function translate(TranslatableInterface $entity, $translations)
    {
        $event = (new TranslationEvent())
            ->setEntity($entity)
            ->setTranslations($translations)
            ->setTarget($this);

        if ($this instanceof EventManagerAwareInterface) {
            $event->setName(TranslationEvent::EVENT_TRANSLATE_PRE);
            $this->getEventManager()->trigger($event);
        }

        $translationMapper = $this->getTranslationMapper();

        foreach ($translations as $locale => $translation) {
            foreach ($translation as $field => $value) {
                if (empty($value)) {
                    continue;
                }
                $translationMapper->translate($entity, $field, $locale, $value);
            }
        }

        if ($this instanceof EventManagerAwareInterface) {
            $event->setName(TranslationEvent::EVENT_TRANSLATE_POST);
            $this->getEventManager()->trigger($event);
        }

        return $entity;
    }

    /**
     * @param  TranslatableInterface $entity
     * @param  mixed                 $locale
     * @return TranslatableInterface
     */
    public function changeLocale(TranslatableInterface $entity, $locale)
    {
        $entity->setLocale($locale);
        $this->getTranslationMapper()->refresh($entity);

        return $entity;
    }

    /**
     * @return TranslationMapperInterface
     */
    abstract public function getTranslationMapper();
}
