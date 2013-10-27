<?php
namespace PHPocket\Widgets;

/**
 * Opengraph builder for page
 * You must ensure, that xml namespace declared as
 * <html prefix="og: http://ogp.me/ns#">
 *
 * @link    http://ogp.me/
 * @link    http://help.yandex.ru/webmaster/video/open-graph-markup.xml
 *
 * @package PHPocket\Widgets
 */
class OpenGraph extends Widget
{

    protected $_data = array();

    /* ======= Basic metadata ======= */
    /**
     * The title of your object as it should appear within the graph
     */
    const TAG_TITLE         = 'title';
    /**
     * The type of your object, e.g., "video.movie". Depending on the type
     * you specify, other properties may also be required
     */
    const TAG_TYPE          = 'type';
    /**
     * An image URL which should represent your object within the graph
     */
    const TAG_IMAGE         = 'image';
    /**
     * The canonical URL of your object that will be used as its permanent
     * ID in the graph
     */
    const TAG_URL           = 'url';

    /* ====  Optional  metadata  ==== */
    /**
     * A URL to an audio file to accompany this object
     */
    const TAG_AUDIO         = 'audio';
    /**
     * A one to two sentence description of your object
     */
    const TAG_DESCRIPTION   = 'description';
    /**
     * The word that appears before this object's title in a sentence.
     * An enum of (a, an, the, "", auto). If auto is chosen, the consumer
     * of your data should chose between "a" or "an". Default is "" (blank)
     */
    const TAG_DETERMINER    = 'determiner';
    /**
     * The locale these tags are marked up in. Of the format
     * language_TERRITORY. Default is en_US
     */
    const TAG_LOCALE        = 'locale';
    /**
     * An array of other locales this page is available in
     */
    const TAG_LOCALE_ALT    = 'locale:alternate';
    /**
     *  If your object is part of a larger web site, the name which
     * should be displayed for the overall site
     */
    const TAG_SITE_NAME     = 'site_name';
    /**
     *  A URL to a video file that complements this object
     */
    const TAG_VIDEO         = 'video';

    /**
     * Utility method to detect https connection
     *
     * @return bool
     */
    protected static function isSSL()
    {
        if ( getenv('https') != '') {
            return true;
        }

        if (getenv('HTTP_X_FORWARDED_PROTO') == 'https') {
            return true;
        }

        return false;
    }


    /**
     * Sets the value of openGraph property
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return OpenGraph
     */
    public function replace($key, $value)
    {
        $this->_data[$key] = array($value);
        return $this;
    }

    /**
     * Sets current URL as permanent URL in openGraph
     *
     * @return OpenGraph
     */
    public function autoUrl()
    {
        return $this->replace(
            self::TAG_TITLE,
            'http' . (self::isSSL() ? 's' : '') . '://'
            . getenv('HTTP_HOST')
            . getenv('REQUEST_URI')
        );
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
                $answer = '';
                foreach ($this->_data as $key => $value) {
                    if (count($value) == 1) {
                        $answer .= '<meta property="og:'
                            . $key
                            . '" content="'
                            . $value[0]
                            .  '" />' . PHP_EOL;
                    } else {
                        foreach ($value as $row) {
                            $answer .= '<meta property="og:'
                                . $key
                                . '" content="'
                                . $row
                                .  '" />' . PHP_EOL;
                        }
                    }
                }
                return $answer;
            default:
                return '';
        }
    }


}