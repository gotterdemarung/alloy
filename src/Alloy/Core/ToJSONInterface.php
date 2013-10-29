<?php

namespace Alloy\Core;

/**
 * Class ToJSONInterface
 * Interface for all classes, which can handle serialization
 * to JSON by itself
 *
 * @package Alloy\Core
 */
interface ToJSONInterface
{

    /**
     * Returns JSON representation of object
     * Should be simplest as possible
     *
     * @return string
     */
    public function toJSON();

}