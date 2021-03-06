<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18nTest;

use MyI18n\Module;
use PHPUnit_Framework_TestCase;
use Gedmo\Translatable\Entity\Repository\TranslationRepository;
use Gedmo\Translatable\TranslatableListener;
use Zend\Console\Console;
use Zend\Mvc\MvcEvent;

class ModuleFunctionalTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider servicesProvider
     * @param $locatorInstance
     * @param $name
     * @param $class
     */
    public function testServiceManagerConfiguration($locatorInstance, $name, $class)
    {
        $serviceLocator = $locatorInstance == 'ServiceManager' ?
            Bootstrap::getServiceManager() :
            Bootstrap::getServiceManager()->get($locatorInstance);

        Bootstrap::getEntityManager($serviceLocator);

        $this->assertTrue($serviceLocator->has($name));
        $this->assertInstanceOf($class, $serviceLocator->get($name));
    }

    public function servicesProvider()
    {
        return [
            ['ServiceManager', 'MyI18n\DataMapper\LocaleMapper', 'MyI18n\DataMapper\LocaleMapper'],
            ['ServiceManager', 'MyI18n\Form\LocaleForm', 'MyI18n\Form\LocaleForm'],
            ['ServiceManager', 'MyI18n\Listener\LocaleAggregateListener', 'MyI18n\Listener\LocaleAggregateListener'],
            ['ServiceManager', 'Gedmo\Translatable\TranslatableListener', 'Gedmo\Translatable\TranslatableListener'],
            ['ServiceManager', 'MyI18n\Navigation', 'Zend\Navigation\Navigation'],
            ['ServiceManager', 'MyI18n\Options\ModuleOptions', 'MyI18n\Options\ModuleOptions'],
            ['ServiceManager', 'MyI18n\Session', 'Zend\Session\Container'],
            ['ControllerLoader', 'MyI18n\Controller\LocaleController', 'MyI18n\Controller\LocaleController'],
            ['ViewHelperManager', 'langTabs', 'MyI18n\View\Helper\LangTabs'],
            ['ViewHelperManager', 'locale', 'MyI18n\View\Helper\Locale'],
            ['ControllerPluginManager', 'locale', 'MyI18n\Controller\Plugin\Locale'],
        ];
    }

    public function testGedmoTranslatableExtensionIntegration()
    {
        $sm = Bootstrap::getServiceManager();
        $em = Bootstrap::getEntityManager($sm);

        /** @var \Gedmo\Translatable\TranslatableListener $listener */
        $listener = $sm->get('Gedmo\Translatable\TranslatableListener');
        $listener->setTranslationFallback(true);
        $listener->setDefaultLocale('it');
        $listener->setTranslatableLocale('it');

        $translatable = new TestAsset\TranslatableEntity();
        $translatable->setId(1);
        $translatable->setText('testo');
        $em->persist($translatable);
        $em->flush();

        $translatable = $em->find(TestAsset\TranslatableEntity::fqcn(), 1);

        /** @var TranslationRepository $repository */
        $repository = $em->getRepository('MyI18n\\Entity\\Translation');
        $repository->translate($translatable, 'text', 'en', 'text');
        $em->persist($translatable);
        $em->flush();

        $translations = $repository->findTranslations($translatable);

        $this->assertCount(1, $translations);

        $translatable = $em->find(TestAsset\TranslatableEntity::fqcn(), 1);
        $translatable->setLocale('en');
        $em->refresh($translatable);

        $this->assertSame('text', $translatable->getText());
    }

    public function testOnBootstrapListenerDoesNothingInConsole()
    {
        $module = new Module();
        $event = $this->getMock('Zend\Mvc\MvcEvent');
        $event->expects($this->never())->method('getApplication');

        $module->onBootstrap($event);
    }

    public function testOnBootstrapListener()
    {
        Console::overrideIsConsole(false);

        $module = new Module();
        $event = new MvcEvent();

        /** @var \Zend\Mvc\Application $application */
        $application = Bootstrap::getServiceManager()->get('Application');

        $eventManager = $this->getMock('Zend\EventManager\EventManager');
        $eventManager->expects($this->atLeastOnce())
            ->method('attach')
            ->with($this->isInstanceOf('MyI18n\Listener\LocaleAggregateListener'));

        $application->setEventManager($eventManager);

        $event->setApplication($application);

        $module->onBootstrap($event);
    }
}
