<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\DataMapper;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping;
use MyBase\DataMapper\MapperEvent;
use MyBase\Doctrine\EntityMapper;
use MyI18n\Entity\Locale;

class LocaleMapper extends EntityMapper implements LocaleMapperInterface
{
    public function __construct($em, Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);

        $this->getEventManager()->attach(MapperEvent::SAVE_PRE, [$this, 'defaultLocaleSwitcherListener']);
        $this->getEventManager()->attach(MapperEvent::REMOVE_POST, [$this, 'ensureDefaultLocaleListener']);
    }

    /**
     * @param  string      $code
     * @return null|Locale
     */
    public function findOneByCode($code)
    {
        return $this->findOneBy(['code' => $code]);
    }

    /**
     * @return array
     */
    public function findAll()
    {
        $order = ['code' => Criteria::ASC];

        return $this->findBy([], $order);
    }

    /**
     * @return array
     */
    public function findAllWithDefaultFirst()
    {
        $order = ['defaultLocale' => Criteria::DESC, 'code' => Criteria::ASC];

        return $this->findBy([], $order);
    }

    /**
     * @return Locale
     */
    public function findLastById()
    {
        $order = ['id' => Criteria::DESC];

        return $this->findOneBy([], $order);
    }

    /**
     * @return null|Locale
     */
    public function findDefaultLocale()
    {
        return $this->findOneBy(['defaultLocale' => true]);
    }

    /**
     * @return array
     */
    public function getAllCodesAsArray()
    {
        $queryBuilder = $this->createQueryBuilder('locale');
        $queryBuilder->addOrderBy('locale.code', Criteria::ASC);

        $result = $queryBuilder->getQuery()->getScalarResult();

        $array = array_map('current', $result);

        return $array;
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
     * save.pre event listener
     *
     * @param MapperEvent $event
     */
    public function defaultLocaleSwitcherListener(MapperEvent $event)
    {
        /** @var Locale $locale */
        $locale = $event->getEntity();

        $default = $this->findDefaultLocale();

        if ($locale->isDefaultLocale() && $default) {
            $default->setDefaultLocale(false);
            $this->getEntityManager()->flush($default);
        }

        if (! $default) {
            $locale->setDefaultLocale();
        }
    }

    /**
     * remove.post event listener
     *
     * @param  MapperEvent $event
     * @return Locale|null
     */
    public function ensureDefaultLocaleListener(MapperEvent $event)
    {
        /** @var Locale $removedLocale */
        $removedLocale = $event->getEntity();

        if (! $removedLocale->isDefaultLocale()) {
            return;
        }

        $lastById = $this->findLastById();

        if (! $lastById) {
            return;
        }

        $lastById->setDefaultLocale();

        $this->save($lastById);
    }
}
