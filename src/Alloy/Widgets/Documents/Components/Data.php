<?php

namespace PHPocket\Widgets\Documents\Components;

/**
 * Utility class for data storing
 *
 * @package PHPocket\Widgets\Documents\Components
 */
class Data implements \Countable
{

    static private $_booleans = array(
        'true', 'y', 'yes', 'on', 'enable', 'enabled'
    );

    /**
     * Container with data
     *
     * @var mixed[]
     */
    private $_data = array();

    /**
     * Countable interface
     *
     * @return int
     */
    public function count()
    {
        return $this->count();
    }

    /**
     * Returns value
     *
     * @param string $key
     * @param mixed  $default
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function get($key, $default = null)
    {
        if ( !isset($this->_data[$key])
            && !array_key_exists($key, $this->_data)
        ) {
            // Not found

            if ($default === null) {
                throw new \InvalidArgumentException(
                    "'{$key}' not found"
                );
            }

            return $default;
        }

        return $this->_data[$key];
    }

    /**
     * Returns int value and zero if not set
     *
     * @param string $key
     * @return int|0
     */
    public function getInt($key)
    {
        return (int) $this->get($key, 0);
    }

    /**
     * Returns true if isset [$key] and !empty([$key])
     *
     * @param string $key
     * @return bool
     */
    public function notEmpty($key)
    {
        if (!isset($key) && !array_key_exists($key, $this->_data)) {
            // Not found
            return false;
        }

        return !empty($this->_data[$key]);
    }


    /**
     * Sets value
     *
     * @param string $key
     * @param mixed  $value
     */
    public function set($key, $value)
    {
        $this->_data[$key] = $value;
    }

    /**
     * Sets bool value
     * Next values assumes as TRUE (others are false):
     * - true (boolean)
     * - true (string, case insensitive)
     * - y
     * - e
     * - yes
     * - on
     * - enable
     * - enabled
     *
     * @param string          $key
     * @param bool|int|string $value
     */
    public function setBool($key, $value)
    {
        if (empty($value)) {
            $this->set($key, false);
        } else if (is_string($value)) {
            $this->set(
                $key,
                in_array(strtolower(trim($value)), self::$_booleans)
            );
        } else {
            $this->set($key, $value === true || $value === 1);
        }
    }

    /**
     * Sets integer value
     * If null or empty provided, assumes 0
     * If not int provided, casts to int
     *
     * @param string $key
     * @param int $value
     */
    public function setInt($key, $value)
    {
        if (empty($value)) {
            $value = 0;
        }

        $this->set($key, (int) $value);
    }

    /**
     * Magic alias for $this->get
     *
     * @param string $key
     * @return mixed
     */
    public final function __get($key)
    {
        return $this->get($key, null);
    }

    /**
     * Magic alias for $this->set
     *
     * @param string $key
     * @param mixed  $value
     */
    public final function __set($key, $value)
    {
        $this->set($key, $value);
    }
}