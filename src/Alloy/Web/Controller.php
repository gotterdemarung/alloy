<?php

namespace Alloy\Web;


use Alloy\Actions\IRunnable;
use Alloy\Core\IObservable;
use Alloy\Core\IObserver;
use Alloy\Observers\Packet;

/**
 * Class Controller
 * Base controller for all web operations
 *
 * @package Alloy\Web
 */
abstract class Controller implements IObservable, IRunnable
{
    /**
     * @var Request
     */
    private $_request;

    /**
     * @var IObserver[]
     */
    private $_observers = array();

    /**
     * Setter for the request
     *
     * @param Request $request
     * @return void
     */
    public function setRequest(Request $request)
    {
        $this->_request = $request;
    }

    /**
     * Returns current controller's request
     *
     * @return Request
     */
    public function getRequest()
    {
        return $this->_request;
    }

    /**
     * Adds new observer
     * @param IObserver $observer
     * @return void
     */
    public function addObserver(IObserver $observer)
    {
        $this->_observers[] = $observer;
    }

    /**
     * Send packet to observers
     *
     * @param Packet $packet
     * @return void
     */
    public function notify(Packet $packet)
    {
        if (count($this->_observers) === 0) {
            return;
        }

        foreach ($this->_observers as $observer) {
            $observer->handlePacket($packet);
        }
    }

    /**
     * Performs an action
     *
     * @return void
     */
    abstract public function run();


} 