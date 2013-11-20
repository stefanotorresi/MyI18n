<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions implements
    DetectorOptionsInterface
{
    /**
     * @var string $defaultLocale
     */
    protected $defaultLocale = 'en';

    /**
     * @var array $supportedLocales
     */
    protected $supportedLocales = [];

    /**
     * @var array $detectors
     */
    protected $detectors = [
        'MyI18n\Detector\Query',
        'MyI18n\Detector\Route',
        'MyI18n\Detector\Session',
        'MyI18n\Detector\Headers',
    ];

    /**
     * @var string $key_name
     */
    protected $key_name = 'lang';

    /**
     * @var NavigationOptions $navigationOptions
     */
    protected $navigationOptions;

    /**
     * @param string $defaultLocale
     */
    public function setDefaultLocale($defaultLocale)
    {
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * @return string
     */
    public function getDefaultLocale()
    {
        return $this->defaultLocale;
    }

    /**
     * @param array $handlers
     */
    public function setDetectors($handlers)
    {
        $this->detectors = $handlers;
    }

    /**
     * @return array
     */
    public function getDetectors()
    {
        return $this->detectors;
    }

    /**
     * @param string $key_name
     */
    public function setKeyName($key_name)
    {
        $this->key_name = $key_name;
    }

    /**
     * @return string
     */
    public function getKeyName()
    {
        return $this->key_name;
    }

    /**
     * @param array $supportedLocales
     */
    public function setSupportedLocales($supportedLocales)
    {
        $this->supportedLocales = $supportedLocales;
    }

    /**
     * @return array
     */
    public function getSupportedLocales()
    {
        return $this->supportedLocales;
    }

    /**
     * @return NavigationOptions
     */
    public function getNavigationOptions()
    {
        return $this->navigationOptions;
    }

    /**
     * @param NavigationOptions $navigationOptions
     */
    public function setNavigationOptions($navigationOptions)
    {
        if (! $navigationOptions instanceof NavigationOptions) {
            $navigationOptions = new NavigationOptions($navigationOptions);
        }

        $this->navigationOptions = $navigationOptions;
    }
}
