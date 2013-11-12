<?php

namespace Alloy\Web\Headers;
use Alloy\Core\IToJSON;
use Alloy\Core\IViewElement;

/**
 * Base class for all headers
 *
 * @package Alloy\Web\Headers
 */
abstract class AbstractHeader implements IToJSON, IViewElement
{
    /**
     * Type of header
     *
     * @var string
     */
    private $_type;

    /**
     * Function, that provides value of the header
     *
     * @return string
     */
    public abstract function getValue();

    /**
     * Constructs an header
     *
     * @param string $type Type of header
     */
    public function __construct($type)
    {
        $this->_type = trim((string)$type);
    }

    /**
     * Returns true if $object is header and internal fields equals to current
     * or $object is this equals to __toString of current header
     *
     * @param mixed $object
     * @return bool
     */
    public function equals($object)
    {
        if (empty($object)) {
            return false;
        }
        if ($object instanceof AbstractHeader) {
            return
                $this->_type == $object->_type
                && $this->getValue() == $object->getValue();
        }
        if (is_string($object)) {
            // Stringcast checking
            return $object === (string) $this;
        }
        return false;
    }

    /**
     * Returns type of current header
     *
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * Returns escaped getValue() for JSON
     *
     * @return string
     */
    protected function _escapeValueJSON()
    {
        if ($this->getValue() === null || $this->getValue() == '') {
            return 'null';
        }
        return '"' . str_replace(
            array('"'),
            array('\\"'),
            $this->getValue()
        ) . '"';
    }

    /**
     * Returns true if provided header has same type
     *
     * @param AbstractHeader $object
     * @return bool
     */
    public function sameType(AbstractHeader $object = null)
    {
        if ($object === null) {
            return false;
        }
        return $this->_type == $object->_type;
    }

    /**
     * Returns simple JSON entry for object
     *
     * @return string
     */
    public function toJSON()
    {
        return '{"' . $this->getType() . '":' . $this->_escapeValueJSON() . '}';
    }

    /**
     * Magic method
     *
     * @return null|string
     */
    public function __toString()
    {
        if ($this->getValue() === null) {
            return null;
        }
        return $this->_type . ': ' . $this->getValue();
    }

}