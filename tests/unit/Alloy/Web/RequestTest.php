<?php

namespace Alloy\Tests\Web;


use Alloy\Tests\unit\AlloyTest;
use Alloy\Type\HashMap;
use Alloy\Web\Request;

class RequestTest extends AlloyTest
{

    /**
     * Internal method to provide test request
     *
     * @return Request
     */
    protected function _getRequest()
    {
        return new Request(
            array('foo' => 'bar', 'x' => 'y'),
            array('one' => 'two', 'id' => 'new', 'x' => 'z'),
            array('cookie1' => 'one', 'cookie2' => 'two'),
            array(
                'HTTP_HOST' => 'google.com',
                'HTTPS' => 'on',
                'REQUEST_URI' => '/search/global?ok=yes#tag'
            )
        );
    }

    public function testGetIp()
    {
        // Saving
        $before1 = getenv('HTTP_X_FORWARDED_FOR');
        $before2 = getenv('HTTP_CLIENT_IP');

        putenv('HTTP_X_FORWARDED_FOR=');
        putenv('HTTP_CLIENT_IP=');

        // In fixture, IP is not set
        $this->assertSame('127.0.0.1', $this->_getRequest()->getIP());

        // setting envs
        putenv('HTTP_X_FORWARDED_FOR=8.8.8.8');
        $this->assertSame('8.8.8.8', $this->_getRequest()->getIP()); // Read from env
        putenv('HTTP_CLIENT_IP=4.4.4.4');
        $this->assertSame('4.4.4.4', $this->_getRequest()->getIP()); // Because higher priority
        putenv('HTTP_X_FORWARDED_FOR='.$before1);

        // Restoring
        putenv('HTTP_X_FORWARDED_FOR=' . $before1);
        putenv('HTTP_CLIENT_IP=' . $before2);

        $this->assertSame('127.0.0.1', $this->_getRequest()->getIP());

        // Reading from _SERVER
        $h = array();
        $h['HTTP_X_FORWARDED_FOR'] = '55.55.55.55';
        $x = new Request(null, null, null, $h);
        $this->assertSame('55.55.55.55', $x->getIP());
        $h['HTTP_X_REAL_IP'] = '44.44.44.44';
        $x = new Request(null, null, null, $h);
        $this->assertSame('44.44.44.44', $x->getIP());
        $h['HTTP_CLIENT_IP'] = '33.33.33.33';
        $x = new Request(null, null, null, $h);
        $this->assertSame('33.33.33.33', $x->getIP());
        $h['REMOTE_ADDR'] = '22.22.22.22';
        $x = new Request(null, null, null, $h);
        $this->assertSame('22.22.22.22', $x->getIP());
    }

    public function testIsMethod()
    {
        $x = new Request();
        $this->assertTrue($x->isMethod('GET'));
        $this->assertFalse($x->isMethod('POST'));
        $this->assertFalse($x->isMethod(null));
        $this->assertFalse($x->isMethod(true));
        $this->assertFalse($x->isMethod(false));

        $x = new Request(null, null, null, array('REQUEST_METHOD' => 'GET'));
        $this->assertTrue($x->isMethod('GET'));
        $this->assertTrue($x->isMethod('get'));
        $this->assertTrue($x->isMethod('Get'));
        $this->assertFalse($x->isMethod('POST'));
        $x = new Request(null, null, null, array('REQUEST_METHOD' => 'POST'));
        $this->assertTrue($x->isMethod('POST'));
        $this->assertFalse($x->isMethod('GET'));


        // Any custom string
        $rand = md5(mt_rand());
        $x = new Request(null, null, null, array('REQUEST_METHOD' => $rand));
        $this->assertTrue($x->isMethod($rand));
        $this->assertFalse($x->isMethod('GET'));
        $this->assertFalse($x->isMethod('POST'));
    }

    public function testIsGet()
    {
        $x = new Request(null, null, null, array('REQUEST_METHOD' => 'GET'));
        $this->assertTrue($x->isGet());
        $x = new Request(null, null, null, array('REQUEST_METHOD' => 'POST'));
        $this->assertFalse($x->isGet());
    }

    public function testIsPost()
    {
        $x = new Request(null, null, null, array('REQUEST_METHOD' => 'POST'));
        $this->assertTrue($x->isPost());
        $x = new Request(null, null, null, array('REQUEST_METHOD' => 'GET'));
        $this->assertFalse($x->isPost());
    }

    public function testIsAjaxXhr()
    {
        $x = new Request(null, null, null, array('REQUEST_METHOD' => 'POST', 'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest'));
        $this->assertTrue($x->isAjaxXhr());
        $x = new Request(null, null, null, array('HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest'));
        $this->assertFalse($x->isAjaxXhr());
        $x = new Request(null, null, null, array('HTTP_X_REQUESTED_WITH' => 'Chrome'));
        $this->assertFalse($x->isAjaxXhr());
        $x = new Request();
        $this->assertFalse($x->isAjaxXhr());
    }

    public function testIsHttps()
    {
        $x = new Request();
        $this->assertFalse($x->isHttps());
        $x = new Request(null, null, null, array('HTTPS' => 'off'));
        $this->assertFalse($x->isHttps());
        $x = new Request(null, null, null, array('HTTPS' => '1'));
        $this->assertTrue($x->isHttps());

        $this->assertTrue($this->_getRequest()->isHttps());
    }

    public function testGetRoutingUrl()
    {
        $this->testGetFullUrl();
    }

    public function testGetFullUrl()
    {
        $this->assertSame('https://google.com/search/global?ok=yes#tag', $this->_getRequest()->getFullRequestUri());
    }

    public function testGetUriPart()
    {
        $this->assertSame('', $this->_getRequest()->getUriPart(-1));
        $this->assertSame('', $this->_getRequest()->getUriPart(0));
        $this->assertSame('search', $this->_getRequest()->getUriPart(1));
        $this->assertSame('global', $this->_getRequest()->getUriPart(2));
        $this->assertSame('', $this->_getRequest()->getUriPart(3));
    }

    public function testGetRequestUri()
    {
        $this->assertSame('/search/global?ok=yes#tag', $this->_getRequest()->getRequestUri());
    }

    public function testAttributes()
    {
        $this->assertTrue($this->_getRequest()->get instanceof HashMap);
        $this->assertTrue($this->_getRequest()->post instanceof HashMap);
        $this->assertTrue($this->_getRequest()->cookie instanceof HashMap);
        $this->assertTrue($this->_getRequest()->server instanceof HashMap);

        $this->assertSame('bar', $this->_getRequest()->get['foo']);
        $this->assertSame('y', $this->_getRequest()->get['x']);

        $this->assertSame('two', $this->_getRequest()->post['one']);
        $this->assertSame('new', $this->_getRequest()->post['id']);
        $this->assertSame('z', $this->_getRequest()->post['x']);

        $this->assertSame('one', $this->_getRequest()->cookie['cookie1']);
        $this->assertSame('two', $this->_getRequest()->cookie['cookie2']);

        $this->assertSame('google.com', $this->_getRequest()->server['HTTP_HOST']);
    }

    public function testArrayAccess()
    {
        $x = $this->_getRequest();

        // On numeric requests it must read uri part
        $this->assertSame('search', $x[1]);
        $this->assertSame('global', $x[2]);
        $this->assertSame('', $x[-1]);

        // On string - read post > get values
        $this->assertSame('bar', $x['foo']);
        $this->assertSame('two', $x['one']);
        $this->assertSame('z', $x['x']);
    }

}