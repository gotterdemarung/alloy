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

    public function testFormat()
    {
        $x = new Timestamp(123456789.76, 2);
        $this->assertSame('1973-11-29 23:33:09', $x->format('Y-m-d H:i:s'));
        $this->assertSame('29.11.73', $x->format('d.m.y'));
    }

    public function testDayBegin()
    {
        $x = new Timestamp(123456789.76, 2);
        $this->assertSame(123372000, $x->getDayBegin()->getInt());
    }

    public function testDayEnd()
    {
        $x = new Timestamp(123456789.76, 2);
        $this->assertSame(123458399, $x->getDayEnd()->getInt());
    }

    public function testGetFloat()
    {
        $x = new Timestamp(123456789.76, 2);
        $this->assertSame(123456789.76, $x->getFloat());
    }

    public function testGetInt()
    {
        $x = new Timestamp(123456789.76, 2);
        $this->assertSame(123456789, $x->getInt());
    }

    public function testGetMySQLDate()
    {
        $x = new Timestamp(123456789.76, 2);
        $this->assertSame('1973-11-29', $x->getMySQLDate());
    }

    public function testGetMySQLDateTime()
    {
        $x = new Timestamp(123456789.76, 2);
        $this->assertSame('1973-11-29 23:33:09', $x->getMySQLDateTime());
    }

    public function testGetUnixTimestamp()
    {
        $x = new Timestamp(123456789.76, 2);
        $this->assertSame(123456789, $x->getUnixTimestamp());
    }

    public function testGetString()
    {
        $x = new Timestamp(12.76345);
        $this->assertSame('12.8', $x->getString(1));
        $this->assertSame('12.763450', $x->getString());
        $this->assertSame('12.763450000', $x->getString(9));
    }

    public function testBiggerThan()
    {
        $x = new Timestamp(12.76345);
        $this->assertTrue($x->isBiggerThen(10));
        $this->assertFalse($x->isBiggerThen(13));
    }

    public function testLesserThan()
    {
        $x = new Timestamp(12.76345);
        $this->assertTrue($x->isLesserThen(20));
        $this->assertFalse($x->isLesserThen(10));
    }

    public function testToJSON()
    {
        $x = new Timestamp(12.76345);
        $this->assertSame('12.763450', $x->toJSON());
    }

    public function testStaticNow()
    {
        $start = microtime(true);
        $x = Timestamp::now()->getFloat();
        $this->assertGreaterThanOrEqual($start, $x);
        $this->assertLessThanOrEqual(microtime(true), $x);
    }

    public function testStaticValid()
    {
        $this->assertTrue(Timestamp::isValidStringTimeStamp('12345'));
        $this->assertTrue(Timestamp::isValidStringTimeStamp('12345.33'));
        $this->assertTrue(Timestamp::isValidStringTimeStamp('-12345'));

        $this->assertFalse(Timestamp::isValidStringTimeStamp('12345,33'));
        $this->assertFalse(Timestamp::isValidStringTimeStamp(PHP_INT_MAX . '1'));
    }

}
