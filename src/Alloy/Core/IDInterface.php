<?php

namespace Alloy\Core;

/**
 * Class IDInterface
 * Interface for all classes, that has an ID
 *
 * @package Alloy\Core
 */
interface IDInterface
{
    /**
     * Returns ID of the object
     *
     * @return Alloy\Type\ID
     */
    public function getID();
}