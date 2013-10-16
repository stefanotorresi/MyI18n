<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\Service;

use MyBase\Service\AbstractEntityService;
use MyI18n\Entity\Locale;

class LocaleService extends AbstractEntityService
{
    public function find($id)
    {
        return $this->getEntityManager()->find(Locale::fqcn(), $id);
    }

    public function findOneByCode($code)
    {
        return $this->getEntityManager()->getRepository(Locale::fqcn())->findOneBy(['code' => $code]);
    }
}
