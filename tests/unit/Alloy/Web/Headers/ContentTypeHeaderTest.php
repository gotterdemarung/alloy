<?php
namespace Alloy\Tests\Web\Headers;


use Alloy\Tests\unit\AlloyTest;
use Alloy\Web\Headers\ContentTypeHeader;

class ContentTypeHeaderTest extends AlloyTest
{

    public function testConstructor()
    {
        $x = new ContentTypeHeader(' iMAge/pNg  ');
        $this->assertSame('Content-Type', $x->getType());
        $this->assertSame('image/png', $x->getValue());
    }

    public function testGetValue()
    {
        $x = new ContentTypeHeader('text/html');
        $this->assertSame('text/html', $x->getValue());
        $x = new ContentTypeHeader('text/plain');
        $this->assertSame('text/plain', $x->getValue());
        $x = new ContentTypeHeader('text/plain', 'utf-8');
        $this->assertSame('text/plain; charset=utf-8', $x->getValue());
        $x = new ContentTypeHeader('text/html', 'utf-8');
        $this->assertSame('text/html; charset=utf-8', $x->getValue());
        $x = new ContentTypeHeader('image/png');
        $this->assertSame('image/png', $x->getValue()); // No charset
    }


}