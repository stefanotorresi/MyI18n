<?php
/**
 * @author Stefano Torresi (http://stefanotorresi.it)
 * @license See the file LICENSE.txt for copying permission.
 * ************************************************
 */

namespace MyI18n\Options;

use Zend\Stdlib\AbstractOptions;

class NavigationOptions extends AbstractOptions
{
    const LABEL_DISPLAY_FULL = 'full';
    const LABEL_DISPLAY_SHORT = 'short';
    const LABEL_DISPLAY_ACTIVE_FULL = 'active';

    /**
     * @var array
     */
    private $validLabelDisplayModes = [
        self::LABEL_DISPLAY_FULL,
        self::LABEL_DISPLAY_SHORT,
        self::LABEL_DISPLAY_ACTIVE_FULL,
    ];

    /**
     * @var string
     */
    protected $labelDisplay = self::LABEL_DISPLAY_FULL;

    /**
     * @var bool
     */
    protected $queryString = false;

    /**
     * @var string
     */
    protected $switchRoute = 'lang-switch';

    /**
     * @var string
     */
    protected $classFormat = 'lang-%s';

    /**
     * @var string
     */
    protected $uriFormat = '?$s=%s';

    /**
     * @param  string                    $labelDisplay
     * @throws \InvalidArgumentException
     */
    public function setLabelDisplay($labelDisplay)
    {
        if (! in_array($labelDisplay, $this->validLabelDisplayModes)) {
            throw new \InvalidArgumentException(sprintf(
               '\'%s\' is not a valid value for \'%s\'',
                $labelDisplay,
                __METHOD__
            ));
        }

        $this->labelDisplay = $labelDisplay;
    }

    /**
     * @return string
     */
    public function getLabelDisplay()
    {
        return $this->labelDisplay;
    }

    /**
     * @param boolean $queryString
     */
    public function setQueryString($queryString)
    {
        $this->queryString = (bool) $queryString;
    }

    /**
     * @return boolean
     */
    public function getQueryString()
    {
        return $this->queryString;
    }

    /**
     * @return string
     */
    public function getSwitchRoute()
    {
        return $this->switchRoute;
    }

    /**
     * @param string $switchRoute
     */
    public function setSwitchRoute($switchRoute)
    {
        $this->switchRoute = $switchRoute;
    }

    /**
     * @return string
     */
    public function getClassFormat()
    {
        return $this->classFormat;
    }

    /**
     * @param string $classFormat
     */
    public function setClassFormat($classFormat)
    {
        $this->classFormat = $classFormat;
    }

    /**
     * @return string
     */
    public function getUriFormat()
    {
        return $this->uriFormat;
    }

    /**
     * @param string $uriFormat
     */
    public function setUriFormat($uriFormat)
    {
        $this->uriFormat = $uriFormat;
    }
}
