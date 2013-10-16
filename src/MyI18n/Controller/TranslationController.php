<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\Controller;

use MyI18n\Entity\Translation;
use MyI18n\Form\TranslationForm;
use MyI18n\Service\TranslationService;
use Zend\EventManager\EventManagerInterface;
use Zend\Http\Response as HttpResponse;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container as Session;
use Zend\View\Model\ViewModel;

class TranslationController extends AbstractActionController
{
    /**
     * @var TranslationForm
     */
    protected $translationForm;

    /**
     * @var TranslationService
     */
    protected $translationService;

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
        $translations = $this->getTranslationService()->getPagedTranslations(
            $this->params('page', 1),
            $this->params('itemsPerPage', 20)
        );

        return [
            'translations' => $translations,
        ];
    }

    public function createAction()
    {
        $form = $this->getTranslationForm();

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

        $this->getTranslationService()->save($translation);
        $this->flashMessenger()->addSuccessMessage('New entry was added successfully');

        return $this->redirect()->toRoute($this->getBaseRoute());
    }

    public function updateAction()
    {
        $translation = $this->getTranslationService()->findTranslation($this->params('id'));

        if (!$translation) {
            return $this->notFoundAction();
        }

        $form = $this->getTranslationForm();
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

        $this->getTranslationService()->save($translation);
        $this->flashMessenger()->addSuccessMessage('Entry was updated successfully');

        return $this->redirect()->toRoute($this->getBaseRoute());
    }

    public function deleteAction()
    {
        $translation = $this->getTranslationService()->findTranslation($this->params('id'));

        if (!$translation) {
            return $this->notFoundAction();
        }

        $this->getTranslationService()->remove($translation);
        $this->flashMessenger()->addSuccessMessage('Entry was deleted successfully');

        return $this->redirect()->toRoute($this->getBaseRoute());
    }

    /**
     * @return TranslationForm
     */
    public function getTranslationForm()
    {
        if (! $this->translationForm) {
            $this->translationForm = $this->getServiceLocator()->get('MyI18n\Form\TranslationForm');
        }

        return $this->translationForm;
    }

    /**
     * @param mixed $translationList
     * @return $this
     */
    public function setTranslationForm($translationList)
    {
        $this->translationForm = $translationList;

        return $this;
    }

    /**
     * @return array|TranslationService|object
     */
    private function getTranslationService()
    {
        if (! $this->translationService) {
            $this->translationService = $this->getServiceLocator()->get('MyI18n\Service\TranslationService');
        }

        return $this->translationService;
    }

    /**
     * @param TranslationService $translationService
     * @return $this
     */
    public function setTranslationService($translationService)
    {
        $this->translationService = $translationService;

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
            $this->getTranslationForm()->setMessages($session->formMessages);
            unset($session->formMessages);
        }

        if (isset($session->formData)) {
            $this->getTranslationForm()->setData($session->formData);
            unset($session->formData);
        }

        $response = $this->getResponse();
        if (isset($session->status) && $response instanceof HttpResponse) {
            $response->setStatusCode($session->status);
            unset($session->status);
        }
    }
}
