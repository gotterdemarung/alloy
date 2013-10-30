<?php

namespace Alloy\Core;

/**
 * Class IDInterface
 * Interface for all classes, that has an ID
 *
 * @package Alloy\Core
 */
interface IID
{
    /**
     * Returns ID of the object
     *
     * @return Alloy\Type\ID
     */
    public function getID();
}