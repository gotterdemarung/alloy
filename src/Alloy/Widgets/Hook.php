<?php

namespace PHPocket\Widgets;

use PHPocket\Widgets\Documents\Document;

/**
 * Base class for all hooks
 *
 *
 * @package PHPocket\Widgets
 */
abstract class Hook extends Widget
{
    /**
     * The document, hook bound to
     *
     * @var Document
     */
    private $_document;

    /**
     * Sets document
     *
     * @param Document $document
     */
    public function setDocument($document)
    {
        $this->_document = $document;
    }

    /**
     * Gets document
     *
     * @return Document
     */
    public function getDocument()
    {
        return $this->_document;
    }

    /**
     * Returns true only if this hook has assigned to it document
     *
     * @return bool
     */
    public function hasDocument()
    {
        return $this->_document !== null;
    }


    /**
     * If true, than this hook must be used in build only
     *
     * @return bool
     */
    public function isBuildOnly()
    {
        return false;
    }
}