<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\DataMapper;

use Gedmo\Translatable\Entity\Repository\TranslationRepository;

class TranslationMapper extends TranslationRepository implements TranslationMapperInterface
{
    public function refresh($entity)
    {
        $this->getEntityManager()->refresh($entity);
    }
}
