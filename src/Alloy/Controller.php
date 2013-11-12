<?php

namespace Alloy;


use Alloy\Actions\IRunnable;
use Alloy\Core\IObservable;
use Alloy\Core\IObserver;
use Alloy\Core\IViewElement;
use Alloy\Observers\Packet;

/**
 * Class Controller
 * @package Alloy
 */
abstract class Controller implements IRunnable, IObservable
{
    /**
     * @var IObserver[]
     */
    private $_observers = array();

    /**
     * Performs an action
     *
     * @return void
     */
    abstract public function run();

    /**
     * Shows or prepares to show view element
     *
     * @param IViewElement $element
     * @return void
     */
    abstract public function show(IViewElement $element);

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