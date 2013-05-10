<?php

/**
 *
 * @author Stefano Torresi <webdeveloper@stefanotorresi.it>
 */

namespace MyI18n;

use Locale;
use Traversable;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\Router\Http\TreeRouteStack;
use Zend\Navigation\Navigation;
use Zend\Navigation\Page\Mvc as MvcPage;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class NavigationFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $services)
    {
        /* @var $router TreeRouteStack  */
        $router = $services->get('router');

        /* @var $request Request */
        $request = $services->get('request');

        /* @var $routeMatch RouteMatch */
        $routeMatch = $router->match($request);

        $globalConfig  = $services->get('config');
        if ($globalConfig instanceof Traversable) {
            $globalConfig = ArrayUtils::iteratorToArray($globalConfig);
        }

        $config = $globalConfig[__NAMESPACE__];
        $currentLocale = Locale::getDefault();

        $pages = array();

        foreach ($config['supported'] as $localeEntry) {

            $fullLangName = ucfirst(Locale::getDisplayLanguage($localeEntry, $localeEntry));

            $page = new MvcPage(array(
                'params'    => array( $config['key_name'] => $localeEntry ),
                'type'      => 'mvc',
                'route'     => 'lang-switch',
                'class'     => 'lang-'.$localeEntry,
                'title'     => $fullLangName,
                'rel'       => array('alternate' => 'alternate'),
            ));

            // set parameters from the currently matched route
            if ($routeMatch) {
                $page->setController($routeMatch->getParam('controller'));
                $page->setAction($routeMatch->getParam('action'));
            }

            $page->setDefaultRouter($router);

            if ($localeEntry == $currentLocale) {
                $page->setActive(true);
                $page->setOrder(-1);
            }

            if ($config['navigation']['full_lang_as_label']) {
                $label = $fullLangName;
            } else {
                $label = strtoupper(Locale::getPrimaryLanguage($localeEntry));
            }

            $page->setLabel($label);

            $pages[] = $page;
        }

        $navigation = new Navigation($pages);

        return $navigation;
    }

}
