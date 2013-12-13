<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\Controller;

use MyI18n\DataMapper\LocaleMapperAwareInterface;
use MyI18n\DataMapper\LocaleMapperInterface;
use MyI18n\Entity\Locale;
use MyI18n\Form\LocaleForm;
use MyI18n\DataMapper\LocaleMapper;
use Zend\Mvc\Controller\AbstractActionController;

class LocaleController extends AbstractActionController
    implements LocaleMapperAwareInterface
{
    const MODE_ENABLE = 'enable';
    const MODE_DISABLE = 'disable';

    /**
     * @var LocaleForm
     */
    protected $localeForm;

    /**
     * @var LocaleMapperInterface
     */
    protected $localeMapper;

    /**
     * @var string
     */
    protected $baseRoute;

    public function indexAction()
    {
        $locales = $this->getLocaleMapper()->findAll();

        return [
            'localeForm' => $this->getLocaleForm(),
            'locales' => $locales,
        ];
    }

    public function enableAction()
    {
        $form           = $this->getLocaleForm();
        $localeMapper  = $this->getLocaleMapper();
        $data           = $this->params()->fromPost();

        $locale = new Locale;
        $form->bind($locale);
        $form->setData($data);

        if ($form->isValid()) {

            if (! $localeMapper->findOneByCode($locale->getCode())) {
                $localeMapper->save($locale);
            }
        }

        return $this->redirect()->toRoute($this->getBaseRoute());
    }

    public function disableAction()
    {
        $code = $this->params()->fromRoute('code');

        $locale = $this->getLocaleMapper()->findOneByCode($code);

        if ($locale instanceof Locale) {
            $this->getLocaleMapper()->remove($locale);
        }

        return $this->redirect()->toRoute($this->getBaseRoute());
    }

    public function makeDefaultAction()
    {
        $code = $this->params()->fromRoute('code');

        $locale = $this->getLocaleMapper()->findOneByCode($code);

        if ($locale instanceof Locale) {
            $this->getLocaleMapper()->makeDefault($locale);
        }

        return $this->redirect()->toRoute($this->getBaseRoute());
    }

    /**
     * @return LocaleForm
     */
    public function getLocaleForm()
    {
        if (! $this->localeForm) {
            $this->localeForm = $this->getServiceLocator()->get('MyI18n\Form\LocaleForm');
        }

        return $this->localeForm;
    }

    /**
     * @param mixed $localeForm
     */
    public function setLocaleForm($localeForm)
    {
        $this->localeForm = $localeForm;
    }

    /**
     * @return LocaleMapperInterface
     */
    public function getLocaleMapper()
    {
        if (! $this->localeMapper) {
            $this->localeMapper = $this->getServiceLocator()->get('MyI18n\Mapper\LocaleMapper');
        }

        return $this->localeMapper;
    }

    /**
     * @param LocaleMapperInterface $localeMapper
     */
    public function setLocaleMapper(LocaleMapperInterface $localeMapper)
    {
        $this->localeMapper = $localeMapper;
    }

    /**
     * @return string
     */
    public function getBaseRoute()
    {
        if (! $this->baseRoute) {
            $config = $this->getServiceLocator()->get('config');
            $this->baseRoute = $config['navigation']['backend']['my-i18n']['route'];
        }

        return $this->baseRoute;
    }

    /**
     * @param string $baseRoute
     */
    public function setBaseRoute($baseRoute)
    {
        $this->baseRoute = $baseRoute;
    }
}
