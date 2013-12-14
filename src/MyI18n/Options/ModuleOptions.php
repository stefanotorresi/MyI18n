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
     * @return NavigationOptions
     */
    public function getNavigationOptions()
    {
        if (! $this->navigationOptions) {
            $this->navigationOptions = new NavigationOptions();
        }

        return $this->navigationOptions;
    }

    /**
     * @param NavigationOptions|array|\Traversable $navigationOptions
     */
    public function setNavigationOptions($navigationOptions)
    {
        if (! $navigationOptions instanceof NavigationOptions) {
            $navigationOptions = new NavigationOptions($navigationOptions);
        }

        $this->navigationOptions = $navigationOptions;
    }
}
