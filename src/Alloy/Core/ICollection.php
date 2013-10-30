<?php

namespace Alloy\Core;

/**
 * Any collection must implement this interface
 *
 *
 * @package Alloy\Common
 */
interface ICollection extends
    \Countable,
    \ArrayAccess,
    \Traversable,
    IEquals
{

    /**
     * Returns true if collection does not contain
     * any elements
     *
     * @return bool
     */
    public function isEmpty();

    /**
     * Returns true if collection contains
     * provided value
     *
     * @param mixed $value
     * @return bool
     */
    public function containsValue($value);
}