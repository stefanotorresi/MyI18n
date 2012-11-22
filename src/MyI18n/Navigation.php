<?php

/**
 *
 * @author Stefano Torresi <webdeveloper@stefanotorresi.it>
 */

namespace MyI18n;

use Locale;
use Zend\Mvc\MvcEvent;
use Zend\Navigation\Navigation as ZendNavigation;
use Zend\Navigation\Page\Mvc as MvcPage;

class Navigation extends ZendNavigation {
    
    public function __construct(MvcEvent $e)
    {
        
        $router     = $e->getRouter();
        $config     = $e->getApplication()->getConfig();
        $match      = $e->getRouteMatch();
            
        $pages = array();

        $supported_langs = $config['translator']['language']['supported'];

        foreach ($supported_langs as $lang) {
            $page = new MvcPage(array(
                'label'     => ucfirst(Locale::getDisplayLanguage($lang, 
                                                    Locale::getDefault())),
                'params'    => array( 'lang' => $lang ),
                'type'      => 'mvc',
                'route'     => 'lang-switch',
                'rel'       => array('alternate' => 'alternate'),
            ));

            $page->setDefaultRouter($router);

            if ($match) {
                $page->setRouteMatch($match);
            }
            
            if ($lang == Locale::getDefault()) {
                $page->setActive(true);
                $page->setOrder(-1);
            }

            $pages[] = $page;
        }
        
        parent::__construct($pages);
    }
    
}