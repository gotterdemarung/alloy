<?php

namespace Alloy\Tests\Type;

use Alloy\Tests\unit\AlloyTest;
use Alloy\Type\Timestamp;

class TimestampTest extends AlloyTest
{

    protected function assertConstruct($expect, $argument, $precision = null)
    {
        $x = new Timestamp($argument, $precision);
        $this->assertSame($expect, $x->getFloat());
    }

    public function testConstructor()
    {
        // Empty value testing
        $this->assertConstruct((float) 0, null);
        $this->assertConstruct((float) 0, false);
        $this->assertConstruct((float) 0, '');
        $this->assertConstruct((float) 0, 0);

        // Raw value
        $this->assertConstruct((float) 12345, 12345); // int
        $this->assertConstruct(12345.678, 12345.678); // float
        $this->assertConstruct(12345.01, (double) 12345.01); // double

        // String timestamp
        $this->assertConstruct(12345.678, '12345.678');
        $this->assertConstruct((float) 12345, '12345');

        // PHP Datetime
        $this->assertConstruct((float) 1382216400, new \DateTime('2013-10-20'));

        // Self
        $this->assertConstruct(12.34, new Timestamp(12.34));

        // String to time
        $this->assertConstruct((float)944165504, '1999-12-02 22:11:44');

        // Precision
        $this->assertConstruct(12.346, 12.3456, 3); // rounding
        $this->assertConstruct(12.3, 12.3456, 1);
        $this->assertConstruct((float) 12, 12.345, 0);
        try {
            $this->assertConstruct((float) 12, 12.345, -2);
            $this->fail();
        } catch (\InvalidArgumentException $e) {
            $this->assertTrue(true);
        }
    }

    public function testEquals()
    {
        $x = new Timestamp(12.344, 2); // precision !!!
        $this->assertTrue($x->equals(12.34));
        $this->assertTrue($x->equals(new Timestamp('12.34')));
        $this->assertFalse($x->equals(12.3456));
        $this->assertFalse($x->equals(12));
    }


}