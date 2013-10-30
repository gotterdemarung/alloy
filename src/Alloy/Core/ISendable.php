<?php

namespace Alloy\Core;

/**
 * Any class, implementing this interface, must be able to send
 * it's contents to output using echo, print or similar methods
 *
 * @package Alloy\Core
 */
interface ISendable
{
    /**
     * Sends contents of object to output
     *
     * @return void
     */
    public function send();
}