<?php

namespace PHPocket\Widgets\Common;

use PHPocket\Widgets\Widget;

/**
 * Widget for numbers with provided precision
 *
 * @package PHPocket\Widgets
 */
class Number extends Widget
{
    protected $_value;
    protected $_precision;

    /**
     * Constructor
     *
     * @param int|float $value
     * @param int       $precision
     */
    public function __construct($value, $precision = -1)
    {
        if ($value === null) {
            $this->_value = 0;
        } else if (is_int($value)) {
            $this->_value = $value;
        } else {
            $this->_value = (float) $value;
        }

        $this->_precision = (int) $precision;
    }

    /**
     * Returns value of widget in requested context
     *
     * @param int $context
     *
     * @return string|array|null Null returned for no content
     */
    public function getValue($context)
    {
        return $this->getWithPrecision();
    }

    /**
     * Returns value with requested precision
     *
     * @return string
     */
    public function getWithPrecision()
    {
        if ($this->_precision < 0) {
            // Negative precision means as-is
            return (string) $this->_value;
        } else if ($this->_precision === 0) {
            // Integer
            return (string) intval($this->_value);
        } else {
            return sprintf('%.'.$this->_precision.'f', $this->_value);
        }
    }

}