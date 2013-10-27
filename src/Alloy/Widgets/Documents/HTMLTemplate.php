<?php

namespace PHPocket\Widgets\Documents;

/**
 * Templated HTML Document
 *
 *
 * @package PHPocket\Widgets\Documents
 */
class HTMLTemplate extends Document
{
    private $_name;
    private $_filename;


    /**
     * Constructor
     *
     * @param string        $templateFilename
     * @param Document|null $prev
     */
    public function __construct($templateFilename, Document $prev = null)
    {
        parent::__construct($prev);
        $this->_filename = $templateFilename;
        $this->_filename = $this->resolveFilename($templateFilename);
    }


    public function resolveFilename($filename)
    {
        throw new \Exception('Not implemented ' . $filename);
    }

    /**
     * Parses template and returns content
     *
     * @param string $templateFilename
     * @return string
     * @throws \InvalidArgumentException
     */
    public function append($templateFilename)
    {
        if (empty($templateFilename)) {
            throw new \InvalidArgumentException('Null argument');
        }

        // Creating
        $tpl = new HTMLTemplate($templateFilename, $this);

        // return
        return (string) $tpl;
    }

    /**
     * Returns value of widget in requested context
     *
     * @param int $context
     *
     * @return string Null returned for no content
     *
     * @throws \BadMethodCallException
     */
    public function getValue($context)
    {
        if (
            $context !== self::HTML_FULL
            && $context !== self::HTML_SIMPLIFIED
        ) {
            throw new \BadMethodCallException('This widget is for HTML only');
        }

        // Starting buffering
        ob_start();
        // Starting benchmark
        $startedAt = microtime(true);
        // Parsing
        include $this->_filename;
        // Registering benchmark values
        $this->performance['out.parse.' . $this->_name] =
            microtime(true) - $startedAt;
        // Return
        return ob_end_clean();
    }

}