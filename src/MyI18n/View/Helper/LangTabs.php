<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\View\Helper;

use Locale;
use MyI18n\Service;
use Zend\View\Helper\AbstractHelper;
use Zend\Stdlib\ArrayUtils;

class LangTabs extends AbstractHelper implements Service\LocaleServiceAwareInterface
{
    use Service\LocaleServiceAwareTrait;

    /**
     * @var array $defaultOptions
     */
    protected $defaultOptions = [
        'default_first' => true,
        'tab_id_prefix' => 'tab',
    ];

    /**
     * @param Service\LocaleService $localeService
     */
    public function __construct(Service\LocaleService $localeService)
    {
        $this->setLocaleService($localeService);
    }

    /**
     * @param  array  $options
     * @return string
     */
    public function __invoke($options = [])
    {
        $options = ArrayUtils::merge($this->defaultOptions, $options);

        $locales = $options['default_first'] ?
            $this->getLocaleService()->getAllWithDefaultFirst()
            : $this->getLocaleService()->getAll();

        if (! $locales) {
            return;
        }

        $markup = '';

        foreach ($locales as $locale) { /** @var $locale \MyI18n\Entity\Locale */
            $markup .= sprintf(
                '<li class="%s"><a href="#%s-%s" data-toggle="tab">%s</a></li>',
                $locale->isDefaultLocale() ? 'active' : '',
                $options['tab_id_prefix'],
                $locale->getCode(),
                Locale::getDisplayLanguage($locale->getCode())
            );
        }

        $markup = sprintf('<ul class="nav nav-tabs">%s</ul>', $markup);

        return $markup;
    }

    /**
     * @return array
     */
    public function getDefaultOptions()
    {
        return $this->defaultOptions;
    }

    /**
     * @param array $defaultOptions
     */
    public function setDefaultOptions($defaultOptions)
    {
        $this->defaultOptions = $defaultOptions;
    }
}
