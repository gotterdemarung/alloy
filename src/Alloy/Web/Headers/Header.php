<?php
namespace Alloy\Web\Headers;

/**
 * Class for any custom header
 *
 * @package Alloy\Web\Headers
 */
class Header extends AbstractHeader
{
    protected $_value;

    /**
     * Constructs a new header
     *
     * @param string $type  type of header
     * @param string $value value of header
     */
    public function __construct($type, $value)
    {
        parent::__construct($type);
        $this->_value = $value;
    }

    /**
     * Function, that provides value of the header
     *
     * @return string
     */
    public function getValue()
    {
        return $this->_value;
    }


}