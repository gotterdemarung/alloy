<?php

namespace PHPocket\Widgets\Twitter;

use PHPocket\Widgets\Hooks\SingletonHTMLString;
use PHPocket\Widgets\Widget;

class TimeLine extends Widget
{
    protected $_widgetID;

    protected $_noHeader;
    protected $_noFooter;
    protected $_noScroll;
    protected $_noBorder;
    protected $_transparent;


    static private $_script;
    /**
     * Returns script
     *
     * @return string
     */
    static public function getScript()
    {
        if (empty(self::$_script)) {
            self::$_script = trim(file_get_contents(__DIR__ . '/timeline.tpl'));
        }
        return self::$_script;
    }

    /**
     * Constructor
     *
     * @param string $widgetID
     * @param bool   $transparent
     * @param bool   $noHeader
     * @param bool   $noFooter
     * @param bool   $noBorder
     * @param bool   $noScroll
     */
    public function __construct(
        $widgetID,
        $transparent = false,
        $noHeader = false,
        $noFooter = false,
        $noBorder = false,
        $noScroll = false
    )
    {
        $this->_widgetID = $widgetID;
        $this->_transparent = $transparent;
        $this->_noHeader = $noHeader;
        $this->_noFooter = $noFooter;
        $this->_noScroll = $noScroll;
        $this->_noBorder = $noBorder;
    }

    /**
     *
     *
     * @return array
     */
    public function getHooks()
    {
        return array(new SingletonHTMLString(self::getScript()));
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
        switch ($context) {
            case self::HTML_FULL:
            case self::HTML_SIMPLIFIED:
                return '<a class="twitter-timeline" href="https://twitter.com/"'
                    . 'data-widget-id="' . $this->_widgetID . '"'
                    . 'data-chrome="'
                    . ($this->_transparent ? 'transparent ' : '')
                    . ($this->_noHeader ? 'noheader ' : '')
                    . ($this->_noFooter ? 'nofooter ' : '')
                    . ($this->_noBorder ? 'noborders ' : '')
                    . ($this->_noScroll ? 'noscrollbar ' : '')
                    . '">&nbsp;</a>' . PHP_EOL;
            default:
                return $this->_widgetID;
        }
    }


}