<?php

namespace Alloy\Actions;

/**
 * Safely executes a main action (RunnableInterface) and after then
 * runs onSuccess runnable (both provided in constructor) if there
 * were no exceptions is main action
 *
 * If there were exceptions in main action:
 * * if $onException handler set, invokes it and passes \Exception as argument
 * * if no handler set, suppresses an error, but terminates main action and
 *  do not run onSuccess action
 *
 * @package Alloy\Actions
 */
class SafeAction implements IRunnable
{

    /**
     * @var IRunnable
     */
    protected $_action;
    /**
     * @var IHandler
     */
    protected $_onException;
    /**
     * @var IRunnable
     */
    protected $_onNoError;

    /**
     * Creates safe action executor
     *
     * @param IRunnable      $action      Main action
     * @param IHandler       $onException Handler to run on error
     * @param IRunnable|null $onSuccess   Action must run on finish
     */
    public function __construct(IRunnable $action,
        IHandler $onException = null,
        IRunnable $onSuccess = null
    )
    {
        $this->_action = $action;
        $this->_onException = $onException;
        $this->_onNoError = $onSuccess;
    }

    /**
     * Performs an action
     *
     * @throws \Exception
     * @return void
     */
    public function run()
    {
        try {
            $this->_action->run();
            if (!empty($this->_onNoError)) {
                $this->_onNoError->run();
            }
        } catch ( \Exception $e ) {
            if (!empty($this->_onException)) {
                $this->_onException->handle($e);
            } else {
                // Ignoring
            }
        }
    }


}