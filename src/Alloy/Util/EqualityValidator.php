<?php
namespace Alloy\Util;
use Alloy\Core\IEquals;

/**
 * Utility class for checking that provided
 * arguments are equal
 *
 * @package Alloy\Util
 */
class EqualityValidator implements IEquals
{

    /**
     * @var bool
     */
    private $_strictPrimitives;

    /**
     * Constructor
     * If set to true, strict (===) equality check used for
     * primitive types
     *
     * @param bool $strict
     */
    public function __construct($strict = true)
    {
        $this->_strictPrimitives = (bool) $strict;
    }

    /**
     * Returns true if validators are equal
     *
     * @param mixed $object
     * @return bool
     */
    public function equals($object)
    {
        return $object !== null
            && is_object($object)
            && $object instanceof EqualityValidator
            && $object->_strictPrimitives === $this->_strictPrimitives;
    }

    /**
     * Returns true only if all provided to function
     * elements equals to each other
     *
     * @param mixed $first
     * @param mixed $second
     *
     * @return bool
     */
    public function areEqual($first, $second)
    {
        if ($first === null && $second === null) {
            // Nulls are equal
            return true;
        }
        if ($first === null || $second === null) {
            // One of arguments is null
            return false;
        }

        if (is_object($first) && $first instanceof IEquals) {
            return $first->equals($second);
        }
        if (is_object($second) && $second instanceof IEquals) {
            return $second->equals($first);
        }

        if (is_array($first) && !is_array($second)) {
            return false;
        }
        if (is_array($second) && !is_array($first)) {
            return false;
        }

        if ($this->_strictPrimitives) {
            return $first === $second;
        } else {
            return $first == $second;
        }
    }

    /**
     * Returns true if needle found inside array
     *
     * @param array|\Traversable $array
     * @param mixed              $needle
     * @param bool               $lookInKeys <p>if true, looks for needle
     * inside keys instead of values</p>
     *
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function inArray($array, $needle, $lookInKeys = false)
    {
        if (empty($array)) {
            return false;
        }
        if (!is_array($array) && !$array instanceof \Traversable) {
            throw new \InvalidArgumentException(
                'Expecting array or Traversable'
            );
        }

        foreach ($array as $key => $value ) {
            if ($lookInKeys && $this->areEqual($needle, $key)) {
                return true;
            }
            if (!$lookInKeys && $this->areEqual($needle, $value)) {
                return true;
            }
        }

        return false;
    }

}