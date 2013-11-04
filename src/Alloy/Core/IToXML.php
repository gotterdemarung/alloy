<?php

namespace Alloy\Core;

/**
 * Interface IToXML
 * Represents interface for entities, which can handle serialization
 * by itself
 *
 * @package Alloy\Core
 */
interface IToXML
{
    /**
     * Returns XML representation of the object
     *
     * @return string
     */
    public function toXML();

} 