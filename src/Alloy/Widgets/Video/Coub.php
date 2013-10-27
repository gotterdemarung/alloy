<?php

namespace PHPocket\Widgets\Video;


use PHPocket\Widgets\Widget;

/**
 * Widget for embedding Coub videos
 * {@link http://coub.com/}
 *
 * @package PHPocket\Widgets\Video
 */
class Coub extends Widget
{
    protected $_id;
    protected $_autoStart;
    protected $_startMuted;
    protected $_hideTop;
    protected $_noSiteButtons;
    protected $_hd;

    protected $_width;
    protected $_height;

    const REGEX_URL = '%https?://coub.com/(view|embed)/([a-z0-9_\-]+)%i';

    /**
     * Parses provided string and looks for coub id inside it
     *
     * @param string $idOrUrl
     * @return string
     * @throws \InvalidArgumentException
     */
    static public function resolveID($idOrUrl)
    {
        if (empty($idOrUrl)) {
            throw new \InvalidArgumentException('ID is empty');
        }
        if (strpos($idOrUrl, '/') === false) {
            // No path separator, assuming that this is clear id
            return $idOrUrl;
        }
        if (preg_match(self::REGEX_URL, $idOrUrl, $matches)) {
            return $matches[2];
        }
        throw new \InvalidArgumentException('Provided ID not valid');
    }

    /**
     * Constructor
     *
     * @param string $idOrUrl
     * @param int   $width
     * @param int   $height
     * @param bool  $autoStart
     * @param bool  $startMuted
     * @param bool  $hideTop
     * @param bool  $noSiteButtons
     * @param bool  $startHD            start with HD quality
     */
    function __construct(
        $idOrUrl,
        $width,
        $height,
        $autoStart = false,
        $startMuted = false,
        $hideTop = false,
        $noSiteButtons = false,
        $startHD = false
    )
    {
        $this->_id = self::resolveID($idOrUrl);
        $this->_autoStart = (bool) $autoStart;
        $this->_hideTop = $this->_autoStart && (bool) $hideTop;
        if ($this->_hideTop) {
            $this->_noSiteButtons = true;
        } else {
            $this->_noSiteButtons = (bool) $noSiteButtons;
        }
        $this->_startMuted = $this->_autoStart && (bool)$startMuted;
        $this->_hd = $this->_autoStart && (bool) $startHD;

        $this->_width = $width;
        $this->_height = $height;
    }

    /**
     * Returns entry url
     *
     * @return string
     */
    public function getEntryUrl()
    {
        return 'http://coub.com/view/' . $this->_id;
    }

    /**
     * Returns embed url
     *
     * @return string
     */
    public function getEmbedUrl()
    {
        return 'http://coub.com/embed/' . $this->_id;
    }

    /**
     * Returns value of widget in requested context
     *
     * @param int $context
     *
     * @return string|null Null returned for no content
     */
    public function getValue($context)
    {
        switch ($context) {
            // HTML Content
            case self::HTML_FULL:
            case self::HTML_SIMPLIFIED:
                return '<iframe '
                    . 'src="'          . $this->getEmbedUrl()
                    . '?muted='        . $this->b2s($this->_startMuted)
                    . '&autostart='     . $this->b2s($this->_autoStart)
                    . '&noSiteButtons=' . $this->b2s($this->_noSiteButtons)
                    . '&hideTopBar='    . $this->b2s($this->_hideTop)
                    . '&startWithHD='   . $this->b2s($this->_hd)
                    . '" allowfullscreen="true" frameborder="0" '
                    . 'width="' . $this->_width
                    . '" height="' . $this->_height . '">'
                    . '</iframe>'
                    ;
            // Plaintext content
            case self::PLAINTEXT:
            // Default is plaintext
            default:
                return $this->getEntryUrl();
        }
    }

    /**
     * Converts bool to string
     *
     * @param bool $bool
     * @return string
     */
    private function b2s($bool)
    {
        return $bool ? 'true' : 'false';
    }
}