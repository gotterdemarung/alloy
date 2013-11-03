<?php

namespace Alloy\Observers;


use Alloy\Core\IObservable;
use Alloy\Core\IObserver;

abstract class Observable implements IObservable
{
    /**
     * @var IObserver[]
     */
    private $_observers = array();

    /**
     * {@inheritdoc}
     */
    public function addObserver(IObserver $observer)
    {
        $this->_observers[] = $observer;
    }

    /**
     * {@inheritdoc}
     */
    public function notify(Packet $packet)
    {
        if (count($this->_observers) === 0) {
            return;
        }

        foreach($this->_observers as $observer) {
            $observer->handlePacket($packet);
        }
    }

    /**
     * Sends message to observers, using NOTICE level
     *
     * @param mixed  $message
     * @param string $tag
     */
    public function log($message, $tag = null)
    {
        if ($tag === null) {
            $tag = get_class($this);
        }
        $this->notify(new Packet($message, $tag, Packet::LEVEL_NOTICE));
    }

}