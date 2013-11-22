<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\Controller;

use MyI18n\Entity\Locale;
use MyI18n\Form\LocaleForm;
use MyI18n\Service\LocaleService;
use Zend\Mvc\Controller\AbstractActionController;

class LocaleController extends AbstractActionController
{
    const MODE_ENABLE = 'enable';
    const MODE_DISABLE = 'disable';

    /**
     * @var LocaleForm
     */
    protected $localeForm;

    /**
     * @var LocaleService
     */
    protected $localeService;

    /**
     * @var string
     */
    protected $baseRoute;

    public function indexAction()
    {
        $locales = $this->getLocaleService()->getAll();

        return [
            'localeForm' => $this->getLocaleForm(),
            'locales' => $locales,
        ];
    }

    public function enableAction()
    {
        $form           = $this->getLocaleForm();
        $localeService  = $this->getLocaleService();
        $data           = $this->params()->fromPost();

        $locale = new Locale;
        $form->bind($locale);
        $form->setData($data);

        if ($form->isValid()) {

            if (! $localeService->findOneByCode($locale->getCode())) {
                $localeService->save($locale);
            }
        }

        return $this->redirect()->toRoute($this->getBaseRoute());
    }

    public function disableAction()
    {
        $code = $this->params()->fromRoute('code');

        $locale = $this->getLocaleService()->findOneByCode($code);

        if ($locale instanceof Locale) {
            $this->getLocaleService()->remove($locale);
        }

        return $this->redirect()->toRoute($this->getBaseRoute());
    }

    public function makeDefaultAction()
    {
        $code = $this->params()->fromRoute('code');

        $locale = $this->getLocaleService()->findOneByCode($code);

        if ($locale instanceof Locale) {
            $this->getLocaleService()->makeDefault($locale);
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
     * @return LocaleService
     */
    public function getLocaleService()
    {
        if (! $this->localeService) {
            $this->localeService = $this->getServiceLocator()->get('MyI18n\Service\LocaleService');
        }

        return $this->localeService;
    }

    /**
     * @param LocaleService $localeService
     */
    public function setLocaleService($localeService)
    {
        $this->localeService = $localeService;
    }

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
