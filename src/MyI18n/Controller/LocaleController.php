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

    public function processAction()
    {
        /** @var \Zend\Http\Request $request */
        $request = $this->getRequest();

        $data = $request->isPost() ? $this->params()->fromPost() : $this->params()->fromRoute();

        $form = $this->getLocaleForm();
        $form->setData($data);

        if ($form->isValid()) {
            $data = $form->getData();
            $code = $data['code'];
            $locale = $this->getLocaleService()->findOneByCode($code);

            if ($data['mode'] === self::MODE_ENABLE && !$locale) {
                $locale = new Locale($code);
                $this->getLocaleService()->save($locale);
            }

            if ($data['mode'] === self::MODE_DISABLE && $locale instanceof Locale) {
                $this->getLocaleService()->remove($locale);
            }
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
