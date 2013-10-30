<?php

namespace Alloy\Actions;

/**
 * This interface is designed to provide a common protocol
 * for objects that wish to execute code while they are active
 *
 * @package Alloy\Actions
 */
interface IRunnable
{

    /**
     * Performs an action
     *
     * @return void
     */
    public function run();
}