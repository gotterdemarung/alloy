<?php

namespace PHPocket\Widgets\Hooks;

use PHPocket\Widgets\Hook;

/**
 * Class SingletonHTMLString
 *
 * Guarantees, that provided data would be displayed only once
 * Useful, when you're using widgets, that refers to scripts
 * Allows to include script once
 *
 * @package PHPocket\Widgets\Hooks
 */
class SingletonHTMLString extends Hook
{
    /**
     * Already displayed elements
     *
     * @var array
     */
    private static $_already = array();

    /**
     * Contents to display
     *
     * @var string
     */
    protected $_string;

    /**
     * Constructor
     *
     * @param string $string
     */
    public function __construct($string)
    {
        $this->_string = $string;
    }

    /**
     * Returns value of widget in requested context
     *
     * @param int $context
     *
     * @return string|array|null Null returned for no content
     */
    public function getValue($context)
    {
        if (!$context == self::HTML_FULL
            && !$context == self::HTML_SIMPLIFIED) {
            // This hook is only for HTML
            return '';
        }


        if ($this->hasDocument()) {
            // Singleton check
            $hash = spl_object_hash($this->getDocument());
            if (!isset(self::$_already[$hash])) {
                // This object has not been displayed yet
                self::$_already[$hash] = array(
                    $this->_string => true
                );
            } else if (!isset(self::$_already[$hash][$this->_string])) {
                // This object has not been displayed yet
                self::$_already[$hash][$this->_string] = true;
            } else {
                // Already displayed
                return '';
            }
        }

        return (string) $this->_string;
    }
}