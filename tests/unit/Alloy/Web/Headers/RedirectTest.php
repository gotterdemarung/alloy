<?php
namespace Alloy\Tests\Web\Headers;


use Alloy\Tests\unit\AlloyTest;
use Alloy\Web\Headers\Redirect;

class RedirectTest extends AlloyTest
{

    public function testConstructor()
    {
        $x = new Redirect('   http://ya.ru ');
        $this->assertSame('Location', $x->getType());
        $this->assertSame('http://ya.ru', $x->getValue());
    }

}