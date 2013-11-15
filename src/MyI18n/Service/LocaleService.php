<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\Service;

use Doctrine\Common\Collections\Criteria;
use MyBase\Service\AbstractEntityService;
use MyI18n\Entity\Locale;

class LocaleService extends AbstractEntityService
{
    /**
     * @param int $id
     * @return null|Locale
     */
    public function find($id)
    {
        return $this->getEntityManager()->find(Locale::fqcn(), $id);
    }

    /**
     * @param string $code
     * @return null|Locale
     */
    public function findOneByCode($code)
    {
        return $this->getEntityManager()->getRepository(Locale::fqcn())->findOneBy(['code' => $code]);
    }

    public function getAll()
    {
        return $this->getEntityManager()->getRepository(Locale::fqcn())->findBy([], ['code' => Criteria::ASC]);
    }
}
