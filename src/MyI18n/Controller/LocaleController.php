<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\Controller;

use MyI18n\Form\LocaleForm;
use MyI18n\Service\LocaleService;
use Zend\EventManager\EventManagerInterface;
use Zend\Http\Response as HttpResponse;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;

class LocaleController extends AbstractActionController
{
    /**
     * @var LocaleForm
     */
    protected $localeForm;

    /**
     * @var LocaleService
     */
    protected $localeService;

    /**
     * @var Session
     */
    protected $session;

    public function setEventManager(EventManagerInterface $events)
    {
        $events->attach(MvcEvent::EVENT_DISPATCH, array($this, 'preDispatch'), 100);

        return parent::setEventManager($events);
    }

    public function indexAction()
    {
        $translations = $this->getLocaleService()->getPagedTranslations(
            $this->params('page', 1),
            $this->params('itemsPerPage', 20)
        );

        return [
            'translations' => $translations,
        ];
    }

    public function createAction()
    {
        $form = $this->getLocaleForm();

        $request = $this->getRequest();

        if (!$request->isPost()) {
            $viewModel = new ViewModel(array(
                'translationForm' => $form
            ));
            $viewModel->setTemplate('my-i18n/translation-form');

            return $viewModel;
        }

        $data = $request->getPost();

        $translation = new Translation();
        $form->bind($translation)->setData($data);

        if (!$form->isValid()) {
            $session = $this->getSession();
            $session->formMessages = $form->getMessages();
            $session->formData = $data;
            $session->status = 400;
            $this->flashMessenger()->addErrorMessage('Invalid data');

            return $this->redirect()->toRoute($this->getBaseRoute().'/translations/create');
        }

        $this->getLocaleService()->save($translation);
        $this->flashMessenger()->addSuccessMessage('New entry was added successfully');

        return $this->redirect()->toRoute($this->getBaseRoute());
    }

    public function updateAction()
    {
        $translation = $this->getLocaleService()->findTranslation($this->params('id'));

        if (!$translation) {
            return $this->notFoundAction();
        }

        $form = $this->getLocaleForm();
        $form->prepareToUpdate($translation);

        $request = $this->getRequest();

        if (!$request->isPost()) {
            $viewModel = new ViewModel(array(
                'translationForm' => $form,
                'translation' => $translation,
            ));
            $viewModel->setTemplate('my-i18n/translation-form');

            return $viewModel;
        }

        $data = $request->getPost();
        $form->setData($data);

        if (!$form->isValid()) {
            $session = $this->getSession();
            $session->formMessages = $form->getMessages();
            $session->formData = $data;
            $session->status = 400;
            $this->flashMessenger()->addErrorMessage('Invalid data');

            return $this->redirect()->toRoute($this->getBaseRoute().'/translations/update', array(), array(), true);
        }

        $this->getLocaleService()->save($translation);
        $this->flashMessenger()->addSuccessMessage('Entry was updated successfully');

        return $this->redirect()->toRoute($this->getBaseRoute());
    }

    public function deleteAction()
    {
        $translation = $this->getLocaleService()->findTranslation($this->params('id'));

        if (!$translation) {
            return $this->notFoundAction();
        }

        $this->getLocaleService()->remove($translation);
        $this->flashMessenger()->addSuccessMessage('Entry was deleted successfully');

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
     * @param mixed $translationList
     * @return $this
     */
    public function setLocaleForm($translationList)
    {
        $this->localeForm = $translationList;

        return $this;
    }

    /**
     * @return LocaleService
     */
    private function getLocaleService()
    {
        if (! $this->localeService) {
            $this->localeService = $this->getServiceLocator()->get('MyI18n\Service\LocaleService');
        }

        return $this->localeService;
    }

    /**
     * @param LocaleService $localeService
     * @return $this
     */
    public function setLocaleService($localeService)
    {
        $this->localeService = $localeService;

        return $this;
    }

    /**
     * @return Session
     */
    public function getSession()
    {
        if (! $this->session) {
            $this->session = new Session(__CLASS__);
        }

        return $this->session;
    }

    public function getBaseRoute()
    {
        $config = $this->getServiceLocator()->get('config');

        return $config['navigation']['backend']['my-i18n']['route'];
    }

    public function preDispatch()
    {
        $session = $this->getSession();

        if (isset($session->formMessages)) {
            $this->getLocaleForm()->setMessages($session->formMessages);
            unset($session->formMessages);
        }

        if (isset($session->formData)) {
            $this->getLocaleForm()->setData($session->formData);
            unset($session->formData);
        }

        $response = $this->getResponse();
        if (isset($session->status) && $response instanceof HttpResponse) {
            $response->setStatusCode($session->status);
            unset($session->status);
        }
    }
}
