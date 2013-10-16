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
use MyI18n\Entity\Translation;
use Zend\EventManager\Event;
use Zend\I18n\Translator\Loader\RemoteLoaderInterface;
use Zend\I18n\Translator\TextDomain;
use Zend\Paginator\Paginator;

class TranslationService extends AbstractEntityService implements RemoteLoaderInterface
{
    /**
     * @var LocaleService
     */
    protected $localeService;

    public function __construct(EntityManager $entityManager, LocaleService $localeService)
    {
        parent::__construct($entityManager);

        $this->localeService = $localeService;
    }

    /**
     * {@inheritdoc}
     */
    public function load($locale, $textDomain)
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $expr = $queryBuilder->expr();

        $queryBuilder->select('translation')->from(Translation::fqcn(), 'translation')
            ->innerJoin('translation.locale', 'locale', $expr->eq('code', $expr->literal($locale)))
            ->where($expr->eq('translation.domain', $expr->literal($textDomain)));

        $translations = $queryBuilder->getQuery()->getResult();

        $result = array();
        foreach ($translations as $translation) { /** @var $translation Translation */
            $result[$translation->getMsgid()] = $translation->getMsgstr();
        }

        return new TextDomain($result);
    }

    /**
     * @param  int       $page
     * @param  int       $itemCountPerPage
     * @return Paginator
     */
    public function getPagedTranslations($page, $itemCountPerPage = 10)
    {
        $repository = $this->getEntityManager()->getRepository(Translation::fqcn());
        $query = $repository->createQueryBuilder('translation');

        return $this->getPagedQuery($query, $page, $itemCountPerPage);
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

    public function findTranslation($idOrMsgid, $domain = null)
    {
        $criteria = $domain === null ?
            [ 'id' => $idOrMsgid ]
            : [
                'msgid' => $idOrMsgid,
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

        if (! $this->findTranslation($message, $domain)) {
            $translation = new Translation();
            $translation->setMsgid($message);
            $translation->setDomain($domain);
            if (! $locale = $this->getLocaleService()->findOneByCode($locale)) {
                $locale = new Locale($locale);
            }
            $translation->setLocale($locale);

            $this->save($translation);
        }
    }

    /**
     * @return LocaleService
     */
    public function getLocaleService()
    {
        return $this->localeService;
    }

    /**
     * @param LocaleService $localeService
     * @return $this
     */
    public function setLocaleService(LocaleService $localeService)
    {
        $this->localeService = $localeService;

        return $this;
    }
}
