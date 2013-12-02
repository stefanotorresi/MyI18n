<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\Service;

use Doctrine\ORM\EntityManager;
use Gedmo\Translatable\Entity\Repository\TranslationRepository;
use MyI18n\Entity\TranslatableInterface;
use MyI18n\Event\TranslationEvent;
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

        /** @var TranslationRepository $translationRepo */
        $translationRepo = $this->getEntityManager()->getRepository('MyI18n\Entity\Translation');

        foreach ($translations as $locale => $translation) {
            foreach ($translation as $field => $value) {
                if (empty($value)) {
                    continue;
                }
                $translationRepo->translate($entity, $field, $locale, $value);
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
        $this->getEntityManager()->refresh($entity);

        return $entity;
    }

    /**
     * @return EntityManager
     */
    abstract public function getEntityManager();
}
