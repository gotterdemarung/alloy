<?php

namespace Alloy\Tests\Actions;


use Alloy\Actions\RepeatingAction;
use Alloy\Tests\unit\AlloyTest;

class RepeatingActionTest extends AlloyTest
{

    // Callable to test
    protected $i = 0;
    public function callableToTest()
    {
        $this->i++;
    }
    // EOF callable

    public function testConstructor()
    {
        $x = new RepeatingAction(null, 5, null);
        $x = new RepeatingAction(null, 5, false);
        $x = new RepeatingAction(array($this,'callableToTest'), 5, null);
        try {
            $x = new RepeatingAction(null, 0, null);
            $this->fail();
        } catch( \Exception $e ){
            $this->assertTrue(true);
        }
        try {
            $x = new RepeatingAction(null, -1, null);
            $this->fail();
        } catch( \Exception $e ){
            $this->assertTrue(true);
        }

    }

    public function testExec()
    {
        $x = new RepeatingAction(null, 13, null);
        $x->add(array($this,'callableToTest'));
        $x->add(array($this,'callableToTest'));
        $this->i = 0;
        $x->run();
        $this->assertEquals(26, $this->i);
    }

    public function testTiming()
    {
        // Timing test
        $start = microtime(true);
        $x = new RepeatingAction(null, 1, .25);
        $x->add(array($this,'callableToTest'));
        $this->i = 0;
        $x->run();
        $delta = microtime(true) - $start;
        $this->assertEquals(1, $this->i);
        $this->assertLessThan(.35, $delta);
        $this->assertGreaterThanOrEqual(0.25, $delta);
    }

}