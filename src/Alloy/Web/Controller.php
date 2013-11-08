<?php

namespace Alloy\Web;


use Alloy\Core\IObservable;
use Alloy\Core\IObserver;
use Alloy\Observers\Packet;

/**
 * Class Controller
 * Base controller for all web operations
 *
 * @package Alloy\Web
 */
class Controller implements IObservable
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
     * Constructor
     *
     * @param Request $request
     */
    public function __construct(Request $request = null)
    {
        if ($request === null) {
            $this->_request = new Request();
        } else {
            $this->_request = $request;
        }
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


} 