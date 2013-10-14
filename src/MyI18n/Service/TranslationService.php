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
use Zend\I18n\Translator\TextDomain;

class TranslationService extends AbstractEntityService implements RemoteLoaderInterface
{
    /**
     * {@inheritdoc}
     */
    public function load($locale, $textDomain)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $expr = $queryBuilder->expr();

        $queryBuilder->select('translation')->from(Translation::fqcn(), 'translation')
            ->where($expr->eq('translation.locale', $expr->literal($locale)))
            ->andWhere($expr->eq('translation.domain', $expr->literal($textDomain)));

        $translations = $queryBuilder->getQuery()->getResult();

        $result = array();
        foreach ($translations as $translation) { /** @var $translation Translation */
            $result[$translation->getMsgid()] = $translation->getMsgstr();
        }

        return new TextDomain($result);
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
