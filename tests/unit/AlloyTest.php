<?php

namespace Alloy\Tests\unit;

/**
 * Class AlloyTest
 *
 * Abstract class for all unit tests
 *
 * @package Alloy\Tests
 */
abstract class AlloyTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Use this method to provide OK status and increase
     * asserts count (in catch block for example)
     *
     * @param string $message
     */
    public function ok($message = null)
    {
        $this->assertTrue(true, $message);
    }


}