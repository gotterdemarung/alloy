<?php

namespace PHPocket\Widgets;


interface WidgetInterface
{
    const PLAINTEXT       = 10;

    const HTML_SIMPLIFIED = 2010;
    const HTML_FULL       = 2020;

    /**
     * Returns value of widget in requested context
     *
     * @param int $context
     *
     * @return string|array|null Null returned for no content
     */
    public function getValue($context);

    /**
     * Any widget must override this method
     * and call for
     * $this->getValue(Widget::getGlobalContext())
     *
     * Must not throw any errors
     * Must not return null, return empty string if there is no data
     *
     * @return string
     */
    public function __toString();
}