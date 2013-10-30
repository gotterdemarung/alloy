<?php

namespace Alloy\Tests\Actions;

use Alloy\Actions\Action;
use Alloy\Actions\IRunnable;
use Alloy\Tests\unit\AlloyTest;

class ActionTest extends AlloyTest
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
        $x = new Action();
        $x = new Action(null);
        $x = new Action(new InnerRunnable());
        try {
            $x = new Action(5);
            $this->fail('Must throw exception');
        } catch (\Exception $e) {
            $this->assertTrue(true);
        }
        try {
            $x = new Action(new InnerNotRunnable());
            $this->fail('Must throw exception');
        } catch (\Exception $e) {
            $this->assertTrue(true);
        }
        try {
            $x = new Action('');
            $this->fail('Must throw exception');
        } catch (\Exception $e) {
            $this->assertTrue(true);
        }
    }

    public function testCallable()
    {
        $x = new Action(array($this,'callableToTest'));
        $this->i = 0;
        $x->run();
        $this->assertEquals(1, $this->i);

        $this->i = 0;
        $x->add(array($this,'callableToTest'));
        $x->run();
        $this->assertEquals(2, $this->i);

        // Another three
        $x->add(array($this,'callableToTest'));
        $x->add(array($this,'callableToTest'));
        $x->add(array($this,'callableToTest'));

        $this->i = 0;
        $x->run();
        $this->assertEquals(5, $this->i);
    }

    public function testRunnable()
    {
        $r = new InnerRunnable();
        $x = new Action($r);
        $x->run();
        $this->assertEquals(1, $r->i);

        $r = new InnerRunnable();
        $x = new Action($r);
        $x->add($r);
        $x->add($r);
        $x->run();
        $this->assertEquals(3, $r->i);
    }
}

class InnerRunnable implements IRunnable
{

    public $i = 0;

    /**
     * Performs an action
     *
     * @return void
     */
    public function run()
    {
        $this->i++;
    }


}

class InnerNotRunnable {}