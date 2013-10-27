<?php

namespace PHPocket\Widgets\Common;

/**
 * Widget for colorized numbers
 *
 * @package PHPocket\Widgets
 */
class NumberColor extends Number
{

    protected $_zeroPositive;
    protected $_cssPositive;
    protected $_cssNegative;
    protected $_cssZero;

    /**
     * Constructor
     *
     * @param int|float $value
     * @param int       $precision
     * @param bool      $zeroIsPositive
     * @param string    $cssPositive
     * @param string    $cssNegative
     * @param string    $cssZero
     */
    public function __construct(
        $value,
        $precision = -1,
        $zeroIsPositive = false,
        $cssPositive = 'positive',
        $cssNegative = 'negative',
        $cssZero = 'zero'
    )
    {
        parent::__construct($value, $precision);
        $this->_zeroPositive = (bool) $zeroIsPositive;
        $this->_cssPositive = $cssPositive;
        $this->_cssNegative = $cssNegative;
        $this->_cssZero = $cssZero;
    }

    /**
     * Colorizes output for full HTML only
     *
     * @param int $context
     * @return array|null|string
     */
    public function getValue($context)
    {
        switch ($context) {
            case self::HTML_FULL:
                return '<span class="'
                    . ( $this->_value < 0 ?
                    $this->_cssNegative
                    :   ( $this->_value > 0 || $this->_zeroPositive ?
                            $this->_cssPositive : $this->_cssZero
                        )
                    ) . '">' . $this->getWithPrecision() . '</span>';
            default:
                return parent::getValue($context);
        }
    }


}