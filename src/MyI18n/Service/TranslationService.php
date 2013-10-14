<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\Service;

use MyBase\Service\AbstractEntityService;
use MyI18n\Entity\Translation;
use Zend\I18n\Translator\Loader\RemoteLoaderInterface;

class TranslationService extends AbstractEntityService implements RemoteLoaderInterface
{
    /**
     * {@inheritdoc}
     */
    public function load($locale, $textDomain)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $expr = $queryBuilder->expr();

        $queryBuilder->select('translation.msgid, translation.msgstr')
            ->from(Translation::fqcn(), 'translation')
            ->where($expr->eq('locale', $expr->literal($locale)))
            ->andWhere($expr->eq('domain', $expr->literal($textDomain)));

        $translations = $queryBuilder->getQuery()->getArrayResult();

        return $translations;
    }

    /**
     * @return array
     */
    public function getAllDomains()
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->select('DISTINCT translation.domain')->from(Translation::fqcn(), 'translation');

        $result  = $queryBuilder->getQuery()->getScalarResult();
        $domains = array();

        foreach ($result as $r) {
            $domains[] = $r['domain'];
        }

        return $domains;
    }
}
