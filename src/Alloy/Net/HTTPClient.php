<?php

namespace Alloy\Net;

use Alloy\Core\IObservable;
use Alloy\Core\IObserver;
use Alloy\Observers\Packet;

class HTTPClient implements IObservable
{
    /**
     * @var array
     */
    private $_cache;

    /**
     * @var IObserver[]
     */
    private $_observers = array();

    /**
     * Helper function for debug-level logging
     *
     * @param mixed $message
     */
    protected function _debug($message)
    {
        if (count($this->_observers) === 0) {
            return;
        }
        $this->notify(
            new Packet(
                $message,
                'HTTPClient',
                Packet::LEVEL_DEBUG
            )
        );
    }

    /**
     * Helper function for notice-level logging
     *
     * @param mixed $message
     */
    protected function _notice($message)
    {
        if (count($this->_observers) === 0) {
            return;
        }
        $this->notify(
            new Packet(
                $message,
                'HTTPClient',
                Packet::LEVEL_NOTICE
            )
        );
    }

    /**
     * Sets cache
     *
     * @param array|\ArrayAccess $cache
     * @throws \InvalidArgumentException
     */
    public function setCache($cache)
    {
        if ($cache === null) {
            throw new \InvalidArgumentException(
                'Null pointer'
            );
        }

        if (!is_array($cache) && !($cache instanceof \ArrayAccess)) {
            throw new \InvalidArgumentException(
                'Expecting array or ArrayAccess'
            );
        }

        $this->_cache = $cache;

        // Logging
        $this->_debug('Setting ' . get_class($this->_cache) . ' as cache');
    }

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

        foreach ($this->_observers as $observer) {
            $observer->handlePacket($packet);
        }
    }

    /**
     * Performs GET request to server
     *
     * @param string $url
     * @param null $requestOptions
     *
     * @return string
     */
    public function get($url, $requestOptions = null)
    {
        // Checking cache
        if ($this->_cache !== null && ($cached = $this->_cache[$url]) !== false) {
            // Cache hit
            $this->_notice("Cache hit on {$url}");
            $this->_notice('Received ' . strlen($cached) . ' bytes');
            return $cached;
        }

        // Logging
        $this->_notice("Preparing request to {$url}");
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $answer = curl_exec($curl);

        if ($answer === false) {
            // curl error
            $this->notify(
                new Packet(
                    "No data received from {$url}",
                    'HTTPClient',
                    Packet::LEVEL_ERROR
                )
            );
            throw new \Exception("No data received from {$url}");
        }

        // Logging
        $this->_notice('Received ' . strlen($answer) . ' bytes');

        // Putting into cache
        if ($this->_cache !== null) {
            $this->_cache[$url] = $answer;
            $this->_notice("Stored in cache");
        }

        return $answer;
    }
}