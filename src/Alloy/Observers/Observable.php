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
     * Returns tag for current object
     *
     * @return string
     */
    protected function _getObservableTag()
    {
        $tag = get_class($this);
        if (strpos($tag, '\\') !== false) {
            $tag = explode('\\', $tag);
            $tag = $tag[count($tag) - 1];
        }
        return $tag;
    }

    /**
     * {@inheritdoc}
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
     * Register an exception and rethrows it
     *
     * @param \Exception $exception
     * @param string $tag
     * @throws \Exception
     */
    protected function _oRethrow($exception, $tag = null)
    {
        if (count($this->_observers) === 0) {
            throw $exception;
        }
        if ($tag === null) {
            $tag = $this->_getObservableTag();
        }
        $this->notify(new Packet($exception, $tag, Packet::LEVEL_ERROR));
        throw $exception;
    }

    /**
     * Sends message to observers, using WARNING level
     *
     * @param mixed  $message
     * @param string $tag
     */
    protected function _oWarn($message, $tag = null)
    {
        if (count($this->_observers) === 0) {
            return;
        }
        if ($tag === null) {
            $tag = $this->_getObservableTag();
        }
        $this->notify(new Packet($message, $tag, Packet::LEVEL_WARNING));
    }

    /**
     * Sends message to observers, using DEBUG level
     *
     * @param mixed  $message
     * @param string $tag
     */
    protected function _oDebug($message, $tag = null)
    {
        if (count($this->_observers) === 0) {
            return;
        }
        if ($tag === null) {
            $tag = $this->_getObservableTag();
        }
        $this->notify(new Packet($message, $tag, Packet::LEVEL_DEBUG));
    }

    /**
     * Sends message to observers, using NOTICE level
     *
     * @param mixed  $message
     * @param string $tag
     */
    protected function _oLog($message, $tag = null)
    {
        if (count($this->_observers) === 0) {
            return;
        }
        if ($tag === null) {
            $tag = $this->_getObservableTag();
        }
        $this->notify(new Packet($message, $tag, Packet::LEVEL_NOTICE));
    }

}