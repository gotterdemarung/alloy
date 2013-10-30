<?php
namespace Alloy\Type;


use Alloy\Core\ICollection;
use Alloy\Core\IType;
use Alloy\Util\EqualityValidator;

/***
 * Class Node
 * Magic class for entities, which could be either scalar or
 * array values.
 * Returns new Node instead of null on accessing in array access
 * way on non-existing keys
 *
 * Common usage - configuration files
 *
 * @todo unit testing
 * @package Alloy\Type
 */
class Node implements ICollection, IType
{
    /**
     * @var mixed
     */
    private $_data;
    /**
     * @var EqualityValidator
     */
    private $_eqValidator;

    /**
     * Constructor
     *
     * @param mixed|null             $data
     * @param EqualityValidator|null $eqValidator
     */
    public function __construct($data = null, $eqValidator = null)
    {
        $this->_data = $data;
        if ($eqValidator === null) {
            $this->_eqValidator = new EqualityValidator(true);
        } else {
            $this->_eqValidator = $eqValidator;
        }
    }

    /**
     * Magic alias for offsetGet
     *
     * @param string $name
     * @return Node
     */
    public function __get($name)
    {
        return $this->offsetGet($name);
    }

    /**
     * Magic alias for offsetSet
     *
     * @param string $name
     * @param mixed  $value
     */
    function __set($name, $value)
    {
        $this->offsetSet($name, $value);
    }


    /**
     * {@inheritdoc}
     */
    public function count()
    {
        if (empty($this->_data)) {
            return 0;
        }
        if (is_array($this->_data) || ($this->_data instanceof \Countable)) {
            return count($this->_data);
        }
        return 0;
    }

    /**
     * Returns integer value
     * If not applicable, then returns $default, but if
     * default not set, throws exception
     *
     * @param mixed|null $default
     * @return int
     * @throws \LogicException
     */
    public function getInt($default = null)
    {
        if (!$this->isInt()) {
            if ($default !== null) {
                return $default;
            }
            throw new \LogicException('Node does not contain int value');
        }

        return (int) $this->_data;
    }

    /**
     * Returns integer value
     * If not applicable, then returns $default, but if
     * default not set, throws exception
     *
     * @param mixed|null $default
     * @return string
     * @throws \LogicException
     */
    public function getString($default = null)
    {
        if (!$this->isString()) {
            if ($default !== null) {
                return $default;
            }
            throw new \LogicException('Node does not contain string value');
        }

        return (string) $this->_data;
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        return empty($this->_data);
    }

    /**
     * Returns true if internal data is array or
     * implements ArrayAccess interface
     *
     * @return bool
     */
    public function isArrayAccess()
    {
        return is_array($this->_data) || ($this->_data instanceof \ArrayAccess);
    }

    /**
     * Returns true if internal data is int
     *
     * @return bool
     */
    public function isInt()
    {
        if ($this->isNull() || is_object($this->_data)) {
            return false;
        }
        return is_int($this->_data) || $this->_data === (string) intval($this->_data);
    }

    /**
     * Returns true if internal data is null
     *
     * @return bool
     */
    public function isNull()
    {
        return $this->_data === null;
    }

    /**
     * Returns true if internal data is string
     *
     * @return bool
     */
    public function isString()
    {
        if ($this->isNull() || is_object($this->_data)) {
            return false;
        }
        return is_string($this->_data);
    }

    /**
     * Returns true if internal data is traversable
     *
     * @return bool
     */
    public function isTraversable()
    {
        if ($this->isNull()) {
            return false;
        }

        return is_array($this->_data) || $this->_data instanceof \Traversable;
    }

    /**
     * {@inheritdoc}
     */
    public function containsValue($value)
    {
        if ($this->_eqValidator->areEqual($this->_data, $value)) {
            return true;
        }

        if (!is_array($this->_data) && !($this->_data instanceof \Traversable)) {
            return false;
        }

        foreach ($this->_data as $v) {
            if ($$this->_eqValidator->areEqual($v, $value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function equals($object)
    {
        if ($object instanceof Node) {
            return $this->_eqValidator->areEqual($this->_data, $object->_data);
        }

        return $this->_eqValidator->areEqual($this->_data, $object);
    }


    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        if (!$this->isArrayAccess()) {
            return false;
        }
        return isset($this->_data);
    }

    /**
     * {@inheritdoc}
     * @return Node
     */
    public function offsetGet($offset)
    {
        if (!$this->isArrayAccess() || !$this->offsetExists($offset)) {
            return new Node(null);
        }
        return $this->_data[$offset];
    }

    /**
     * {@inheritdoc}
     * @throws \BadMethodCallException
     */
    public function offsetSet($offset, $value)
    {
        if ($this->isNull()) {
            $this->_data = array();
        } else if (! $this->isArrayAccess()) {
            throw new \BadMethodCallException(
                'Node is not array node'
            );
        }

        $this->_data[$offset] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        if ($this->isArrayAccess()) {
            unset($this->_data[$offset]);
        }
    }


} 