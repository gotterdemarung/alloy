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

    public function toJSON();

}