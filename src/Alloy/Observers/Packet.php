<?php

namespace Alloy\Observers;
use Alloy\Type\TimestampUTC;

/**
 * Message packet for observers
 * @package Alloy\Observers
 */
class Packet
{
    /**
     * By default, debug level packets should be dropped
     */
    const LEVEL_DEBUG       = 1;
    /**
     * Minimal level
     */
    const LEVEL_NOTICE      = 2;
    /**
     * Warnings
     */
    const LEVEL_WARNING     = 3;
    /**
     * Errors. By default, on error application should halt
     */
    const LEVEL_ERROR       = 4;

    /**
     * @var float
     */
    private $_time;
    /**
     * @var int
     */
    protected $_level;
    /**
     * @var string|null
     */
    protected $_tag;
    /**
     * @var object|string
     */
    protected $_message;

    /**
     * Constructor
     *
     * @param mixed  $message
     * @param string $tag
     * @param int    $level
     */
    public function __construct($message, $tag = null, $level = self::LEVEL_NOTICE)
    {
        $this->_time = microtime(true);
        $this->_message = $message;
        $this->_tag = $tag;
        $this->_level = $level;
        if ($message instanceof \Exception && ($level === null || ($level === self::LEVEL_NOTICE || $level === self::LEVEL_DEBUG))) {
            // Raising to error
            $this->_level = self::LEVEL_ERROR;
        }
    }

    /**
     * Returns message time
     *
     * @return TimestampUTC
     */
    public function getTime()
    {
        return new TimestampUTC($this->_time);
    }

    /**
     * Returns message
     *
     * @return mixed
     */
    public function getMessage()
    {
        return $this->_message;
    }

    /**
     * Returns message's string
     *
     * @return string
     */
    public function getMessageText()
    {
        try {
            return (string) $this->_message;
        } catch (\Exception $e) {
            return 'Cannot read string.' . $e->getMessage();
        }
    }

    /**
     * Returns level of message
     *
     * @return int
     */
    public function getLevel()
    {
        return $this->_level;
    }

    /**
     * Returns tag
     *
     * @return null|string
     */
    public function getTag()
    {
        return $this->_tag;
    }

    /**
     * To string cast
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getMessageText();
    }

}