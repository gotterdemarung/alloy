<?php

namespace PHPocket\Widgets;

/**
 * Class LinkedWidget
 * Represents widget, which depends on another widgets, used on same
 * presentation level
 *
 * @package PHPocket\Widgets
 */
abstract class LinkedWidget extends Widget
{
    /**
     * @var LinkedWidget
     */
    private $_attachedTo = null;

    /**
     * @var LinkedWidget
     */
    private static $_globalWidgetContainer;


    /**
     * Returns current global widget container
     *
     * @return LinkedWidget
     */
    static public function getGlobalWidgetContainer()
    {
        return self::$_globalWidgetContainer;
    }

    /**
     * Sets current global widget container
     *
     * @param LinkedWidget $target
     */
    static public function setGlobalWidgetContainer(LinkedWidget $target)
    {
        self::$_globalWidgetContainer = $target;
    }



    /**
     * Attaches current widget to another one
     *
     * @param LinkedWidget $target
     *
     * @return $this
     */
    public function attachTo(LinkedWidget $target)
    {
        $this->_attachedTo = $target;
    }

}