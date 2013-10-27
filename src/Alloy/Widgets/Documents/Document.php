<?php

namespace PHPocket\Widgets\Documents;

use PHPocket\Widgets\Documents\Components\Data;
use PHPocket\Widgets\Hook;
use PHPocket\Widgets\Widget;

/**
 * Base document
 * Used as content storage
 *
 * @package PHPocket\Widgets\Documents
 */
class Document extends Widget
{

    /**
     * Document's data
     *
     * @var Data
     */
    public $data;

    /**
     * Performance statistics
     *
     * @var array
     */
    public $performance;

    /**
     * List of assigned hooks
     *
     * @var Hook[]
     */
    protected $_hooks;

    /**
     * Constructor
     * Sets root to self
     *
     * @param Document $prev Previous document
     */
    public function __construct(Document $prev = null)
    {
        if ($prev !== null) {
            $this->linkDataFrom($prev);
        } else {
            // We are first element
            $this->data = new Data();
            $this->performance = array();
        }

        // Registering itself as root document
        Widget::$_currentDocument = $this;
    }

    /**
     * Inherits data from provided document
     *
     * @param Document $doc
     */
    public function linkDataFrom(Document $doc)
    {
        $this->data = $doc->data;
        $this->performance = &$doc->performance;
    }

    /**
     * Registers new hook
     *
     * @param Hook $hook
     */
    public function registerHook(Hook $hook)
    {
        $this->_hooks[] = $hook;
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
        return 'Stub document for ' . $context;
    }


}