<?php

namespace Alloy\Type;


use Alloy\Core\IToJSON;
use Alloy\Core\TypeInterface;

/**
 * Class TimestampPrecise
 * High precision unix timestamp
 *
 * @todo tests needed
 * @package Alloy\Type
 */
class TimestampPrecise implements TypeInterface, IToJSON
{
    /**
     * Value of timestamp
     *
     * @var float
     */
    protected $_value;

    /**
     * Amount of digits after comma
     * Default 6 (for microtime)
     *
     * @var int
     */
    protected $_precision;

    /**
     * Return true if provided string is valid timestamp value
     *
     * @param string $timestampString
     * @return bool
     */
    static public function isValidStringTimeStamp($timestampString)
    {
        // Special assert for PHP DateTime object
        // because it cannot be cast to string
        if ($timestampString instanceof \DateTime) {
            return false;
        }

        // String cast
        $timestampString = (string) $timestampString;

        // Validating
        $answer = ((string) (float) $timestampString === $timestampString)
            && ($timestampString <= PHP_INT_MAX)
            && ($timestampString >= ~PHP_INT_MAX);

        return $answer;
    }

    /**
     * Returns current time
     *
     * @param int $precision
     * @return TimestampPrecise
     */
    static public function now($precision = 6)
    {
        return new self(microtime(true), $precision);
    }

    /**
     * Constructor
     *
     * @param int|float|string|\DateTime|TimestampPrecise $value
     * @param int|null                                    $precision
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($value, $precision = null)
    {
        // Validating precision
        if ($precision === null) {
            $this->_precision = 6;
        } else {
            if ($precision < 0) {
                throw new \InvalidArgumentException(
                    'Precision should be in range 0-9'
                );
            }
            $this->_precision = (int) $precision;
        }

        // Validating value
        if (empty($value)) {
            $this->_value = 0;
        } else if (is_float($value) || is_double($value) || is_int($value)) {
            // Plain numeric timestamp
            $this->_value = (float) $value;
        } else if ($value instanceof TimestampPrecise) {
            // Own type
            $this->_value = $value->_value;
            if ($precision === null) {
                // No precision set, copying from value
                $this->_precision = $value->_precision;
            }
        } else if ($value instanceof \DateTime) {
            // PHP DateTime object
            $this->_value = $value->getTimestamp();
        } else if (self::isValidStringTimeStamp($value)) {
            // String representation
            $this->_value = (float) $value;
        } else {
            // strtotime
            $this->_value =strtotime($value);
        }
    }

    /**
     * Returns after comma part of object
     *
     * @return float
     */
    protected function _getDetail()
    {
        return $this->_value - intval($this->_value);
    }

    /**
     * {@inheritdoc}
     */
    public function equals($object)
    {
        if ($object === null) {
            return false;
        }
        if (!($object instanceof TimestampPrecise)) {
            $object = new TimestampPrecise($object);
        }

        return $this->_value == $object->_value;
    }

    /**
     * Returns date in specific format
     *
     * @param string $format http://php.net/manual/en/function.date.php
     * @return bool|string
     */
    public function format( $format )
    {
        return date($format, $this->getUnixTimestamp());
    }

    /**
     * Returns new TimestampPrecise, representing begin of day
     *
     * @return TimestampPrecise
     */
    public function getDayBegin()
    {
        return new self(
            mktime(
                0,
                0,
                0,
                $this->format('m'),
                $this->format('d'),
                $this->format('Y')
            )
        );
    }

    /**
     * Returns new TimestampPrecise, representing end of day
     *
     * @return TimestampPrecise
     */
    public function getDayEnd()
    {
        return new self(
            mktime(
                23,
                59,
                59,
                $this->format('m'),
                $this->format('d'),
                $this->format('Y')
            )
        );
    }


    /**
     * Returns value of timestamp
     *
     * @return float
     */
    public function getFloat()
    {
        return $this->_value;
    }

    /**
     * Returns int part of timestamp
     *
     * @return int
     */
    public function getInt()
    {
        return (int) $this->_value;
    }

    /**
     * Returns date in MySQL TIMESTAMP format string
     *
     * @return string
     */
    public function getMySQLDateTime()
    {
        return $this->format('Y-m-d H:i:s');
    }

    /**
     * Returns date in MySQL DATE format string
     *
     * @return string
     */
    public function getMySQLDate()
    {
        return $this->format('Y-m-d');
    }


    /**
     * Return unix timestamp (integer)
     *
     * @return int
     */
    public function getUnixTimestamp()
    {
        return $this->getInt();
    }

    /**
     * Returns string representation of timestamp
     * Precision is optional and if omitted, method uses
     * object's precision
     *
     * @param int|null $precision
     * @return string
     * @throws \InvalidArgumentException
     */
    public function getString($precision = null)
    {
        if ($precision === null) {
            $precision = $this->_precision;
        } else {
            if ($precision < 0) {
                throw new \InvalidArgumentException(
                    'Precision should be in range 0-9'
                );
            }
            $precision = (int) $precision;
        }

        if ($precision === 0) {
            return (string) intval($this->_value);
        } else {
            return sprintf("$.{$precision}f", $this->_value);
        }
    }

    /**
     * Returns true if current object timestamp bigger then provided
     *
     * @param mixed|TimestampPrecise $object
     * @return bool
     */
    public function isBiggerThen($object)
    {
        $object = new self($object);
        return $this->_value > $object->_value;
    }

    /**
     * Returns true this current object timestamp lesser then provided
     *
     * @param mixed|TimestampPrecise $object
     * @return bool
     */
    public function isLesserThen($object)
    {
        $object = new self($object);
        return $this->_value < $object->_value;
    }


    /**
     * {@inheritdoc}
     */
    public function toJSON()
    {
        return $this->getString();
    }


}