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
use Zend\Mvc\Application;
use Zend\Navigation\Navigation;
use Zend\Navigation\Page\AbstractPage;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;

class NavigationFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var $application Application  */
        $application = $serviceLocator->get('Application');

        /** @var Options\ModuleOptions $options */
        $options = $serviceLocator->get('MyI18n\Options\ModuleOptions');

        /** @var LocaleMapper $localeMapper */
        $localeMapper = $serviceLocator->get('MyI18n\DataMapper\LocaleMapper');

        $mvcEvent       = $application->getMvcEvent();
        $router         = $mvcEvent->getRouter();
        $routeMatch     = $mvcEvent->getRouteMatch();
        $locales        = $localeMapper->findAll();
        $currentLocale  = IntlLocale::getDefault();
        $navOptions     = $options->getNavigationOptions();

        $pages = array();

        foreach ($locales as $locale) /** @var Locale $locale */ {

            $fullLangName = ucfirst(IntlLocale::getDisplayLanguage($locale, $locale));
            $pageConfig = $navOptions->getQueryString() ?
                array(
                    'type'  => 'uri',
                    'uri'   => sprintf($navOptions->getUriFormat(), $options->getKeyName(), $locale),
                ) :
                array(
                    'params'        => array( $options->getKeyName() => $locale->getCode() ),
                    'type'          => 'mvc',
                    'route'         => $navOptions->getSwitchRoute(),
                    'router'        => $router,
                    'route_match'   => $routeMatch
                );

            $page = AbstractPage::factory(ArrayUtils::merge($pageConfig, array(
                'class' => sprintf($navOptions->getClassFormat(), $locale),
                'title' => $fullLangName,
                'rel'   => array('alternate' => 'alternate'),
            )));

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

            $pages[] = $page;
        }

        $navigation = new Navigation($pages);

        return $navigation;
    }

}
