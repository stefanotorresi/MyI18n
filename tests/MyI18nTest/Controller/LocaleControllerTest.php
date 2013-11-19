<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18nTest\Controller;

use MyI18n\Controller\LocaleController;
use MyI18n\Entity\Locale;
use MyI18n\Form\LocaleForm;
use Zend\Http\Request;
use Zend\Http\Response;
use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\View\Http\CreateViewModelListener;

class LocaleControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LocaleController
     */
    protected $controller;

    public function setUp()
    {
        $localeService = $this->getMock('MyI18n\Service\LocaleService', [], [], '', false);
        $localeForm =  $this->getMock('MyI18n\Form\LocaleForm', [], [], '', false);
        $baseRoute = 'someroute';

        $router =  $this->getMock('Zend\Mvc\Router\RouteStackInterface');
        $router->expects($this->any())
            ->method('assemble')
            ->with([], ['name' => $baseRoute])
            ->will($this->returnValue('someurl'));

        $this->controller = new LocaleController();
        $this->controller->setLocaleService($localeService);
        $this->controller->setLocaleForm($localeForm);
        $this->controller->setBaseRoute($baseRoute);
        $this->controller->getEventManager()->attach(new CreateViewModelListener());
        $this->controller->getEvent()->setRouter($router);
    }

    public function testLocaleServiceLazyGetter()
    {
        $serviceLocator = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');
        $serviceLocator
            ->expects($this->once())
            ->method('get')
            ->with('MyI18n\Service\LocaleService')
            ->will($this->returnValue($this->controller->getLocaleService()));

        $this->controller->setServiceLocator($serviceLocator);
        $this->controller->setLocaleService(null);

        $this->assertInstanceOf('MyI18n\Service\LocaleService', $this->controller->getLocaleService());
    }

    public function testLocaleFormLazyGetter()
    {
        $serviceLocator = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');
        $serviceLocator
            ->expects($this->once())
            ->method('get')
            ->with('MyI18n\Form\LocaleForm')
            ->will($this->returnValue($this->controller->getLocaleForm()));

        $this->controller->setServiceLocator($serviceLocator);
        $this->controller->setLocaleForm(null);

        $this->assertInstanceOf('MyI18n\Form\LocaleForm', $this->controller->getLocaleForm());
    }

    public function testBaseRouteLazyGetter()
    {
        $serviceLocator = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');
        $serviceLocator
            ->expects($this->once())
            ->method('get')
            ->with('config')
            ->will($this->returnValue(include 'config/module.config.php'));

        $this->controller->setServiceLocator($serviceLocator);
        $this->controller->setBaseRoute(null);

        $this->assertInternalType('string', $this->controller->getBaseRoute());
        $this->assertNotEmpty($this->controller->getBaseRoute());
    }

    public function testIndexAction()
    {
        $this->controller->getLocaleService()
            ->expects($this->once())
            ->method('getAll');

        $this->controller->getEvent()->setRouteMatch(new RouteMatch(['action' => 'index']));
        $result = $this->controller->dispatch(new Request());

        $this->assertInstanceOf('Zend\View\Model\ModelInterface', $result);
        $this->assertInstanceOf('MyI18n\Form\LocaleForm', $result->getVariable('localeForm'));
    }

    public function testProcessActionWithInvalidForm()
    {
        $this->controller->getLocaleForm()
            ->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));

        $this->controller->getEvent()->setRouteMatch(new RouteMatch(['action' => 'process']));

        /** @var Response $result */
        $result = $this->controller->dispatch(new Request());

        $this->assertInstanceOf('Zend\Http\Response', $result);
        $this->assertTrue($result->isRedirect());
        $this->assertEquals('someurl', $result->getHeaders()->get('Location')->getUri());
    }

    public function testProcessActionEnable()
    {
        $form = new LocaleForm();
        $this->controller->setLocaleForm($form);

        $this->controller->getLocaleService()
            ->expects($this->once())
            ->method('save');

        $this->controller->getEvent()->setRouteMatch(new RouteMatch(['action' => 'process']));

        $request = new Request();
        $request->setMethod(Request::METHOD_POST);
        $request->getPost()->set('code', 'en');
        $request->getPost()->set('mode', LocaleController::MODE_ENABLE);

        /** @var Response $result */
        $result = $this->controller->dispatch($request);

        $this->assertTrue($form->isValid());
    }

    public function testProcessActionDisable()
    {
        $form = new LocaleForm();
        $this->controller->setLocaleForm($form);

        $this->controller->getLocaleService()
            ->expects($this->once())
            ->method('findOneByCode')
            ->with('en')
            ->will($this->returnValue(new Locale('en')));

        $this->controller->getLocaleService()
            ->expects($this->once())
            ->method('remove');

        $this->controller->getEvent()->setRouteMatch(new RouteMatch(['action' => 'process']));

        $request = new Request();
        $request->setMethod(Request::METHOD_POST);
        $request->getPost()->set('code', 'en');
        $request->getPost()->set('mode', LocaleController::MODE_DISABLE);

        /** @var Response $result */
        $result = $this->controller->dispatch($request);

        $this->assertTrue($form->isValid());
    }
}
