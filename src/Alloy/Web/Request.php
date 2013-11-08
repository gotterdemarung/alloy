<?php

namespace Alloy\Web;

use Alloy\Type\HashMap;

/**
 * Objects, incapsulating all request arguments
 * It has ArrayAccess with next behavior:
 * * on int returns uri part
 * * on string returns post > get
 *
 * @package Alloy\Web
 */
class Request implements \ArrayAccess
{
    /**
     * @var HashMap
     */
    public $get;
    /**
     * @var HashMap
     */
    public $post;
    /**
     * @var HashMap
     */
    public $cookie;
    /**
     * @var HashMap
     */
    public $server;
    /**
     * Parts of request uri, splitted by /
     *
     * @var string[]
     */
    private $_uriParts;

    /**
     * List of $_SERVER keys, containing client IP address
     * @var string[]
     */
    private static $_IPSOURCEPRIORITY = array(
        'REMOTE_ADDR',
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_REAL_IP'
    );

    /**
     * Constructor
     * Inflates and fills all request-specific arguments
     */
    public function __construct()
    {
        // Reading $_GET variables
        if (isset($_GET) && count($_GET) > 0) {
            $this->get = new HashMap($_GET);
        } else {
            $this->get = new HashMap();
        }

        // Reading $_POST variables
        if (isset($_POST) && count($_POST) > 0) {
            $this->post = new HashMap($_POST);
        } else {
            $this->post = new HashMap();
        }

        // Reading $_COOKIE variables
        if (isset($_COOKIE) && count($_COOKIE) > 0) {
            $this->cookie = new HashMap($_COOKIE);
        } else {
            $this->cookie = new HashMap();
        }

        // Reading $_SERVER
        if (isset($_SERVER) && count($_SERVER) > 0) {
            $this->server = new HashMap($_SERVER);
        } else {
            $this->server = new HashMap();
        }

        // Splitting
        $effective = $this->getRequestUri();
        if (($offset = strpos($effective, '?')) > 0) {
            $effective = substr($effective, 0, $offset);
        }
        if (($offset = strpos($effective, '#')) > 0) {
            $effective = substr($effective, 0, $offset);
        }
        $this->_uriParts = explode('/', $effective);
    }

    /**
     * Returns current client IP address
     *
     * @return string
     */
    public function getIP()
    {
        // environment
        $envvar = getenv('HTTP_CLIENT_IP');
        if (!empty($envvar) && $envvar !== '127.0.0.1') {
            return $envvar;
        }
        $envvar = getenv('HTTP_X_FORWARDED_FOR');
        if (!empty($envvar) && $envvar !== '127.0.0.1') {
            return $envvar;
        }

        // server
        foreach (self::$_IPSOURCEPRIORITY as $ips) {
            if (isset($this->server[$ips]) && $this->server[$ips] !== '127.0.0.1') {
                return $this->server[$ips];
            }
        }

        // no hit
        return '127.0.0.1';
    }


    /**
     * Returns raw POST data
     * Not works on multipart-formdata, quote:
     * "php://input is not available with enctype="multipart/form-data""
     *
     * @return string
     */
    public function getRawPost()
    {
        return file_get_contents("php://input");
    }

    /**
     * Returns true if this request was sent by $method
     * Available arguments (case-insensitive):
     * * GET
     * * POST
     * * PUT
     * * HEAD
     * * DELETE
     * * OPTIONS
     *
     * @param string $method
     * @return bool
     */
    public function isMethod($method)
    {
        return strtolower($method) === strtolower($this->server['REQUEST_METHOD']);
    }

    /**
     * Returns true if current request method is GET
     *
     * @return bool
     */
    public function isGet()
    {
        return $this->isMethod('GET');
    }

    /**
     * Returns true if current request method is POST
     *
     * @return bool
     */
    public function isPost()
    {
        return $this->isMethod('POST');
    }

    /**
     * Returns true if current request is post request and
     * was sent from ajax with HTTP_X_REQUESTED_WITH = XMLHttpRequest
     *
     * @return bool
     */
    public function isAjaxXhr()
    {
        return $this->isPost()
            && strtolower($this->server['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * Returns true if current request is HTTPS
     *
     * @return bool
     */
    public function isHttps()
    {
        return isset($this->server['HTTPS']) && $this->server['HTTPS'] !== 'off';
    }

    /**
     * The url, used for routing
     *
     * @return string
     */
    public function getRoutingUri()
    {
        return $this->getFullRequestUri();
    }

    /**
     * Returns full url of current request
     *
     * @return string
     */
    public function getFullRequestUri()
    {
        return 'http'
            . ($this->isHttps() ? 's' : '')
            . '://'
            . $this->server['HTTP_HOST']
            . $this->getRequestUri();
    }

    /**
     * Returns request uri
     *
     * @return mixed
     */
    public function getRequestUri()
    {
        return $this->server['REQUEST_URI'];
    }

    /**
     * Returns uri part and empty string on miss
     *
     * @param int $offset
     * @return string
     */
    public function getUriPart($offset)
    {
        if (!is_int($offset) || $offset < 0 || $offset >= count($this->_uriParts)) {
            return '';
        }

        return $this->_uriParts[$offset];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        if (is_int($offset)) {
            return $offset >= 0 && $offset < count($this->_uriParts);
        }
        if (is_object($offset)) {
            return false;
        }

        return isset($this->post[$offset])
        || isset($this->get[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        if (is_int($offset)) {
            return $this->getUriPart($offset);
        }

        if (isset($this->post[$offset])) {
            return $this->post[$offset];
        }

        if (isset($this->get[$offset])) {
            return $this->get[$offset];
        }

        return '';
    }

    /**
     * Throws exception always
     *
     * @throws \BadMethodCallException
     */
    public function offsetSet($offset, $value)
    {
        throw new \BadMethodCallException();
    }

    /**
     * Throws exception always
     *
     * @throws \BadMethodCallException
     */
    public function offsetUnset($offset)
    {
        throw new \BadMethodCallException();
    }
}