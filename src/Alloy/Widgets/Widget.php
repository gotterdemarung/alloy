<?php

namespace PHPocket\Widgets;

/**
 * Static container for current global display context
 * You can override it value by calling
 * Widget::setGlobalContext(), the argument MUST be one
 * of the WidgetInterface's constants
 *
 * Also provides nice helpers for other widgets
 *
 * @package PHPocket\Widgets
 */
abstract class Widget implements WidgetInterface
{

    /**
     * Current global context
     *
     * @var int
     */
    static private $_currentGlobalContext = null;

    /**
     * Sets current global context
     *
     * @see WidgetInteface for details
     * @param int $value
     *
     * @return void
     */
    static public function setGlobalContext($value)
    {
        self::$_currentGlobalContext = $value;
    }

    /**
     * Returns current global display context
     * If not set before, tries to autodetect it
     *
     * @return int
     */
    static public function getGlobalContext()
    {
        if (empty(self::$_currentGlobalContext)) {
            // Autodetect & force HTML
            self::$_currentGlobalContext = WidgetInterface::HTML_FULL;
        }

        return self::$_currentGlobalContext;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function __toString()
    {
        $context = self::getGlobalContext();
        try {
            $value = $this->getValue($context);
            if ($value === null) return '';
            return $value;
        } catch (\Exception $e) {
            return '';
        }
    }

}