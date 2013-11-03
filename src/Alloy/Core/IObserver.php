<?php

namespace Alloy\Core;


use Alloy\Observers\Packet;

interface IObserver
{
    /**
     * Handle received packet
     *
     * @param Packet $packet
     * @return void
     */
    public function handlePacket(Packet $packet);
}