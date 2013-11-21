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

        $this->getEventManager()->attach('save.pre', [$this, 'preSave']);
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
     * @param Event $event
     */
    public function preSave(Event $event)
    {
        $locale = $event->getParam('entity');

        if ($locale->isDefaultLocale() && $default = $this->getDefaultLocale()) {
            $default->setDefaultLocale(false);
            $this->save($default);
        };
    }
}
