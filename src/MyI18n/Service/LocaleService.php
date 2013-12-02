<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\Service;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use MyBase\Service\AbstractEntityService;
use MyI18n\Entity\Locale;
use Zend\EventManager\Event;

class LocaleService extends AbstractEntityService
{
    public function __construct(EntityManager $entityManager)
    {
        parent::__construct($entityManager);

        $this->getEventManager()->attach('save.pre', [$this, 'defaultLocaleSwitcherListener']);
        $this->getEventManager()->attach('remove.post', [$this, 'ensureDefaultLocaleListener']);
    }

    /**
     * @param  int         $id
     * @return null|Locale
     */
    public function find($id)
    {
        return $this->getEntityManager()->find(Locale::fqcn(), $id);
    }

    /**
     * @param  string      $code
     * @return null|Locale
     */
    public function findOneByCode($code)
    {
        return $this->getEntityManager()->getRepository(Locale::fqcn())->findOneBy(['code' => $code]);
    }

    /**
     * @return array
     */
    public function getAll()
    {
        $order = ['code' => Criteria::ASC];

        return $this->getEntityManager()->getRepository(Locale::fqcn())->findBy([], $order);
    }

    /**
     * @return array
     */
    public function getAllCodesAsArray()
    {
        $queryBuilder = $this->getEntityManager()->getRepository(Locale::fqcn())->createQueryBuilder('locale');
        $queryBuilder->addOrderBy('locale.code', Criteria::ASC);

        $result = $queryBuilder->getQuery()->getScalarResult();

        $array = array_map('current', $result);

        return $array;
    }

    /**
     * @return null|Locale
     */
    public function getDefaultLocale()
    {
        return $this->getEntityManager()->getRepository(Locale::fqcn())->findOneBy(['defaultLocale' => true]);
    }

    /**
     * @return array
     */
    public function getAllWithDefaultFirst()
    {
        $order = ['defaultLocale' => Criteria::DESC, 'code' => Criteria::ASC];

        return $this->getEntityManager()->getRepository(Locale::fqcn())->findBy([], $order);
    }

    /**
     * save.pre event listener
     *
     * @param Event $event
     */
    public function defaultLocaleSwitcherListener(Event $event)
    {
        /** @var Locale $locale */
        $locale = $event->getParam('entity');

        if ($locale->isDefaultLocale() && $oldDefault = $this->getDefaultLocale()) {
            $oldDefault->setDefaultLocale(false);
            $this->getEntityManager()->flush($oldDefault);
        };
    }

    /**
     * remove.post event listener
     *
     * @param  Event       $event
     * @return Locale|null
     */
    public function ensureDefaultLocaleListener(Event $event)
    {
        /** @var Locale $removedLocale */
        $removedLocale = $event->getParam('entity');

        if (! $removedLocale->isDefaultLocale()) {
            return;
        }

        $lastById = $this->getLastById();

        if (! $lastById) {
            return;
        }

        $lastById->setDefaultLocale();

        $this->save($lastById);
    }

    /**
     * @param  Locale $locale
     * @return Locale
     */
    public function makeDefault(Locale $locale)
    {
        $locale->setDefaultLocale(true);
        $this->save($locale);

        return $locale;
    }

    /**
     * @return Locale
     */
    public function getLastById()
    {
        $order = ['id' => Criteria::DESC];

        return $this->getEntityManager()->getRepository(Locale::fqcn())->findOneBy([], $order);
    }
}
