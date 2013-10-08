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
use Zend\Navigation\Page\AbstractPage;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;

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
            $pageConfig = $config['navigation']['query_uri'] ?
                array(
                    'type'  => 'uri',
                    'uri'   => '?'.$config['key_name'].'='.$localeEntry
                ) :
                array(
                    'params'        => array( $config['key_name'] => $localeEntry ),
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

            if ($config['navigation']['full_lang_as_label'] === true
                    || ($config['navigation']['full_lang_as_label'] === 'only_active' && $page->isActive())
            ) {
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
