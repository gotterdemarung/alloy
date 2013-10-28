<?php

namespace Alloy\Tests\Widgets\Common;


use Alloy\Widgets\Common\Number;
use Alloy\Tests\Widgets\AbstractWidgetTest;

class NumberTest extends AbstractWidgetTest
{
    public function testPrecision()
    {
        $x = new Number(123.456, -1);
        $this->assertSame('123.456', (string) $x);
        $x = new Number(123.456, 0);
        $this->assertSame('123', (string) $x);
        $x = new Number(123.456, 1);
        $this->assertSame('123.5', (string) $x);
        $x = new Number(123.456, 2);
        $this->assertSame('123.46', (string) $x);
        $x = new Number(123.456, 5);
        $this->assertSame('123.45600', (string) $x);
    }

    public function testNull()
    {
        $x = new Number(null, 5);
        $this->assertSame('0.00000', (string) $x);
    }

    public function testDefault()
    {
        $x = new Number('1.234');
        $this->assertSame('1.234', (string) $x);
    }
}