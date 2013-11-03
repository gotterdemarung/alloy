<?php

namespace Alloy\Core;


use Alloy\Observers\Packet;

interface IObservable
{

    /**
     * Adds new observer
     * @param IObserver $observer
     * @return void
     */
    public function addObserver(IObserver $observer);

    /**
     * Send packet to observers
     *
     * @param Packet $packet
     * @return void
     */
    public function notify( Packet $packet );
}