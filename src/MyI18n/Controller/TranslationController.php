<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\Controller;

use MyI18n\Form\TranslationForm;
use MyI18n\Service\TranslationService;
use Zend\Mvc\Controller\AbstractActionController;

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

    public function indexAction()
    {
        $translationForm = $this->getTranslationForm();
        $translationService = $this->getTranslationService();
        $translationService->getPaged();

        return [
            'translationForm' => $translationForm,
        ];
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
}
