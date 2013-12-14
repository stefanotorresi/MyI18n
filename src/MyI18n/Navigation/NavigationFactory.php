<?php

/**
 *
 * @author Stefano Torresi <webdeveloper@stefanotorresi.it>
 */

namespace MyI18n\Navigation;

use Locale as IntlLocale;
use MyI18n\Entity\Locale;
use MyI18n\Options;
use MyI18n\DataMapper\LocaleMapper;
use Zend\Mvc\Router\RouteStackInterface;
use Zend\Navigation\Navigation;
use Zend\Navigation\Page\AbstractPage;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;
use Zend\Stdlib\Request;

class NavigationFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var Options\ModuleOptions $options */
        $options = $serviceLocator->get('MyI18n\Options\ModuleOptions');

        /** @var LocaleMapper $localeMapper */
        $localeMapper = $serviceLocator->get('MyI18n\DataMapper\LocaleMapper');

        $currentLocale  = IntlLocale::getDefault();
        $locales        = $localeMapper->findAll();
        $navigation     = new Navigation();
        $navOptions     = $options->getNavigationOptions();

        if ($navOptions->getQueryString()) {
            $pageConfig = [
                'type'  => 'uri',
            ];
        } else {
            /* @var $router RouteStackInterface  */
            $router = $serviceLocator->get('router');

            /* @var $request Request */
            $request = $serviceLocator->get('request');

            $routeMatch     = $router->match($request);

            $pageConfig = [
                'type'      => 'mvc',
                'route'     => $navOptions->getSwitchRoute(),
                'router'    => $router,
            ];

            if ($routeMatch) {
                $pageConfig['routeMatch'] = $routeMatch;
            }
        }

        foreach ($locales as $locale) {
            /** @var Locale $locale */

            $fullLangName = ucfirst(IntlLocale::getDisplayLanguage($locale, $locale));

            if ($pageConfig['type'] === 'uri') {
                $pageConfig['uri'] = sprintf($navOptions->getUriFormat(), $options->getKeyName(), $locale);
            } else {
                $pageConfig['params'] = [ $options->getKeyName() => $locale->getCode() ];
            }

            $pageConfig['class']    = sprintf($navOptions->getClassFormat(), $locale);
            $pageConfig['title']    = $fullLangName;
            $pageConfig['rel']      = ['alternate' => 'alternate'];

            $page = AbstractPage::factory($pageConfig);

            if ($locale == $currentLocale) {
                $page->setActive(true);
                $page->setOrder(-1);
            }

            switch ($navOptions->getLabelDisplay()) {
                case Options\NavigationOptions::LABEL_DISPLAY_SHORT :
                    $label = strtoupper(IntlLocale::getPrimaryLanguage($locale));
                    break;

                case Options\NavigationOptions::LABEL_DISPLAY_ACTIVE_FULL :
                    $label = $page->isActive() ?
                        $fullLangName :
                        strtoupper(IntlLocale::getPrimaryLanguage($locale));
                    break;

                case Options\NavigationOptions::LABEL_DISPLAY_FULL :
                default :
                    $label = $fullLangName;
            }

            $page->setLabel($label);

            $navigation->addPage($page);
        }

        return $navigation;
    }

}
