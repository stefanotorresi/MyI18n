<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18nTest\Controller;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use MyI18n\Controller\LocaleController;
use MyI18n\Entity\Locale;
use MyI18n\Form\LocaleForm;
use MyI18nTest\Bootstrap;
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
        $localeMapper = $this->getMock('MyI18n\DataMapper\LocaleMapper', [], [], '', false);
        $localeForm =  $this->getMock('MyI18n\Form\LocaleForm', [], [], '', false);
        $baseRoute = 'someroute';

        $router =  $this->getMock('Zend\Mvc\Router\RouteStackInterface');
        $router->expects($this->any())
            ->method('assemble')
            ->with([], ['name' => $baseRoute])
            ->will($this->returnValue('someurl'));

        $this->controller = new LocaleController();
        $this->controller->setLocaleMapper($localeMapper);
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
            ->with('MyI18n\DataMapper\LocaleMapper')
            ->will($this->returnValue($this->controller->getLocaleMapper()));

        $this->controller->setServiceLocator($serviceLocator);
        $this->controller->setLocaleMapper(null);

        $this->assertInstanceOf('MyI18n\DataMapper\LocaleMapper', $this->controller->getLocaleMapper());
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
        $this->controller->getLocaleMapper()
            ->expects($this->once())
            ->method('findAll');

        $this->controller->getEvent()->setRouteMatch(new RouteMatch(['action' => 'index']));
        $result = $this->controller->dispatch(new Request());

        $this->assertInstanceOf('Zend\View\Model\ModelInterface', $result);
        $this->assertInstanceOf('MyI18n\Form\LocaleForm', $result->getVariable('localeForm'));
    }

    public function testEnableActionWithInvalidForm()
    {
        $this->controller->getLocaleForm()
            ->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));

        $this->controller->getLocaleMapper()
            ->expects($this->never())
            ->method('save');

        $this->controller->getEvent()->setRouteMatch(new RouteMatch(['action' => 'enable']));

        /** @var Response $result */
        $result = $this->controller->dispatch(new Request());

        $this->assertInstanceOf('Zend\Http\Response', $result);
        $this->assertTrue($result->isRedirect());
        $this->assertEquals('someurl', $result->getHeaders()->get('Location')->getUri());
    }

    public function testEnableAction()
    {
        $sm = Bootstrap::getServiceManager();
        $em = Bootstrap::getEntityManager($sm);

        $form = new LocaleForm();
        $hydrator = new DoctrineObject($em);

        $form->setHydrator($hydrator);
        $this->controller->setLocaleForm($form);

        $this->controller->getLocaleMapper()
            ->expects($this->once())
            ->method('save')
            ->with($this->equalTo(new Locale('en')));

        $this->controller->getEvent()->setRouteMatch(new RouteMatch(['action' => 'enable']));

        $request = new Request();
        $request->setMethod(Request::METHOD_POST);
        $request->getPost()->set('code', 'en');

        /** @var Response $result */
        $result = $this->controller->dispatch($request);

        $this->assertTrue($form->isValid());
    }

    public function testDisableAction()
    {
        $locale = new Locale('en');

        $this->controller->getLocaleMapper()
            ->expects($this->once())
            ->method('findOneByCode')
            ->with('en')
            ->will($this->returnValue($locale));

        $this->controller->getLocaleMapper()
            ->expects($this->once())
            ->method('remove')
            ->with($locale);

        $this->controller->getEvent()->setRouteMatch(new RouteMatch(['action' => 'disable', 'code' => 'en']));

        $request = new Request();

        /** @var Response $result */
        $result = $this->controller->dispatch($request);
    }

    public function testMakeDefaultAction()
    {
        $locale = new Locale('en');

        $this->controller->getLocaleMapper()
            ->expects($this->once())
            ->method('findOneByCode')
            ->with('en')
            ->will($this->returnValue($locale));

        $this->controller->getLocaleMapper()
            ->expects($this->once())
            ->method('makeDefault')
            ->with($locale);

        $this->controller->getEvent()->setRouteMatch(new RouteMatch(['action' => 'make-default', 'code' => 'en']));

        $request = new Request();

        /** @var Response $result */
        $result = $this->controller->dispatch($request);
    }
}
