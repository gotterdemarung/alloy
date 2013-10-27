<?php

namespace PHPocket\Core;

/**
 * Any collection must implement this interface
 *
 *
 * @package PHPocket\Common
 */
interface CollectionInterface extends
    \Countable,
    \ArrayAccess,
    \Traversable,
    EqualsInterface
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