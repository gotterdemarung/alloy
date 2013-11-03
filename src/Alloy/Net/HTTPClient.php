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
        $this->notify(
            new Packet(
                'Setting ' . get_class($this->_cache) . ' as cache',
                'HTTPClient',
                Packet::LEVEL_DEBUG
            )
        );
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
        // Logging
        $this->notify(
            new Packet(
                "Preparing request to {$url}",
                'HTTPClient',
                Packet::LEVEL_NOTICE
            )
        );
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $answer = curl_exec($curl);

        // Logging
        $this->notify(
            new Packet(
                'Received ' . strlen($answer) . ' bytes',
                'HTTPClient',
                Packet::LEVEL_NOTICE
            )
        );

        return $answer;
    }
}