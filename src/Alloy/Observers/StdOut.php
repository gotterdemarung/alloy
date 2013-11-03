<?php

namespace Alloy\Observers;

use Alloy\Core\IObserver;

class StdOut implements IObserver
{
    /**
     * {@inheritdoc}
     */
    public function handlePacket(Packet $packet)
    {
        echo '[', $packet->getTime()->format('y-m-d H:i:s'), '] ';
        if ($packet->getTag() !== null) {
            echo '[', $packet->getTag(), '] ';
        }
        echo $packet->getMessageText(), PHP_EOL;
    }
}