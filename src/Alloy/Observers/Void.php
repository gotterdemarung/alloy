<?php

namespace Alloy\Observers;


use Alloy\Core\IObserver;

class Void implements IObserver
{
    /**
     * {@inheritdoc}
     */
    public function handlePacket(Packet $packet)
    {
        // do nothing
    }

}