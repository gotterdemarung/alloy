<?php
namespace Alloy\Core;

/**
 * Any object, providing equals(Object another) method
 * must implement this interface
 *
 * @package Alloy\Core
 */
interface IEquals
{
    /**
     * Compares itself to $object and return true if
     * object are of the same type and value
     *
     * @param mixed $object Object to compare
     * @return mixed
     */
    public function equals($object);
}