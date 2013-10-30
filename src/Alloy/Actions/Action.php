<?php

namespace Alloy\Actions;

/**
 * Wrapper for any callable
 * Implements runnable interface
 *
 * @package Alloy\Actions
 */
class Action implements IRunnable
{
    /**
     * @var callable[]|IRunnable[]
     */
    private $_callable = array();

    /**
     * Creates an action for callable or runnable
     *
     * @param callable|IRunnable $callableOrRunnable
     * @throws \InvalidArgumentException on invalid or null data
     */
    public function __construct($callableOrRunnable = null)
    {
        if ($callableOrRunnable != null) {
            $this->add($callableOrRunnable);
        }
    }

    /**
     * Add an action to execution pool
     *
     * @param callable|IRunnable $callableOrRunnable
     * @throws \InvalidArgumentException
     * @return void
     */
    public function add($callableOrRunnable)
    {
        if (empty($callableOrRunnable)) {
            throw new
            \InvalidArgumentException(
                'Expecting callable or RunnableInterface, null received'
            );
        }
        if (
            !is_callable($callableOrRunnable)
            && !($callableOrRunnable instanceof IRunnable)
        ) {
            throw new
            \InvalidArgumentException(
                'Expecting callable or RunnableInterface'
            );
        }
        $this->_callable[] = $callableOrRunnable;
    }

    /**
     * Performs an action
     *
     * @return void
     */
    public function run()
    {
        if (count($this->_callable) == 0) {
            // No tasks
            return;
        }
        foreach ($this->_callable as $action) {
            if ($action instanceof IRunnable) {
                $action->run();
            } else {
                call_user_func($action);
            }
        }
    }
}