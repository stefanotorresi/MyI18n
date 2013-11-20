<?php

/**
 *
 * @author Stefano Torresi <webdeveloper@stefanotorresi.it>
 */

namespace MyI18n;

use Locale;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\Router\Http\TreeRouteStack;
use Zend\Navigation\Navigation;
use Zend\Navigation\Page\AbstractPage;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;

class NavigationFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var $router TreeRouteStack  */
        $router = $serviceLocator->get('router');

        /* @var $request Request */
        $request = $serviceLocator->get('request');

        /* @var $routeMatch RouteMatch */
        $routeMatch = $router->match($request);

        /** @var Options\ModuleOptions $options */
        $options = $serviceLocator->get('MyI18n\Options\ModuleOptions');

        /** @var Service\LocaleService $localeService */
        $localeService = $serviceLocator->get('MyI18n\ServiceLocaleService');
        $locales = $localeService->getAll();

        $currentLocale = Locale::getDefault();

        $pages = array();

        foreach ($locales as $localeEntry) {

            $fullLangName = ucfirst(Locale::getDisplayLanguage($localeEntry, $localeEntry));
            $pageConfig = $options->getNavigationOptions()->getQueryString() ?
                array(
                    'type'  => 'uri',
                    'uri'   => '?'.$options->getKeyName().'='.$localeEntry
                ) :
                array(
                    'params'        => array( $options->getKeyName() => $localeEntry ),
                    'type'          => 'mvc',
                    'route'         => 'lang-switch',
                    'router'        => $router,
                    'route_match'   => $routeMatch
                );

            $page = AbstractPage::factory(ArrayUtils::merge($pageConfig, array(
                'class' => 'lang-'.$localeEntry,
                'title' => $fullLangName,
                'rel'   => array('alternate' => 'alternate'),
            )));

            if ($localeEntry == $currentLocale) {
                $page->setActive(true);
                $page->setOrder(-1);
            }

            switch ($options->getNavigationOptions()->getLabelDisplay()) {
                case Options\NavigationOptions::LABEL_DISPLAY_FULL :
                    $label = $fullLangName;
                    break;

                case Options\NavigationOptions::LABEL_DISPLAY_ACTIVE_FULL :
                    if ($page->isActive()) {
                        $label = $fullLangName;
                        break;
                    }

                case Options\NavigationOptions::LABEL_DISPLAY_SHORT :
                default :
                    $label = strtoupper(Locale::getPrimaryLanguage($localeEntry));
            }

            $page->setLabel($label);

            $pages[] = $page;
        }

        $navigation = new Navigation($pages);

        return $navigation;
    }

}
