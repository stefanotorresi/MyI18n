<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\Service;

use MyBase\Service\AbstractEntityService;
use MyI18n\Entity\Translation;
use Zend\EventManager\Event;
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

    public function findTranslation($msgid, $locale, $domain)
    {
        $criteria = [
            'msgid' => $msgid,
            'locale' => $locale,
            'domain' => $domain,
        ];

        $result = $this->getEntityManager()->getRepository(Translation::fqcn())->findOneBy($criteria);

        return $result;
    }

    public function missingTranslationListener(Event $e)
    {
        $message = $e->getParam('message');
        $locale = $e->getParam('locale');
        $domain = $e->getParam('text_domain');

        if (! $this->findTranslation($message, $locale, $domain)) {
            $translation = new Translation();
            $translation->setMsgid($message);
            $translation->setLocale($locale);
            $translation->setDomain($domain);
            $this->save($translation);
        }
    }
}
