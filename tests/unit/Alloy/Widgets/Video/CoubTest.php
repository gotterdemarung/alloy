<?php

namespace Alloy\Tests\Widgets\Video;

use Alloy\Tests\Widgets\AbstractWidgetTest;
use Alloy\Widgets\Video\Coub;

class CoubTest extends AbstractWidgetTest
{

    public function testIDResolve()
    {
        $this->assertSame('abcedfg12', Coub::resolveID('http://coub.com/view/abcedfg12'));
        $this->assertSame('abcedfg12', Coub::resolveID('https://coub.com/view/abcedfg12'));
        $this->assertSame('abcedfg12', Coub::resolveID('http://coub.com/embed/abcedfg12'));
        $this->assertSame('abcedfg12', Coub::resolveID('abcedfg12'));

        try {
            Coub::resolveID('http://youtube.com/embed/abcedfg12');
            $this->fail();
        } catch (\InvalidArgumentException $e){
            $this->assertTrue(true);
        }
    }

    public function testTpl()
    {
        $this->assertTpl(
            __DIR__ . '/Coub.json',
            'Alloy\Widgets\Video\Coub'
        );
    }

    public function testPlain()
    {
        $x = new Coub('http://coub.com/embed/557l1v80' , 20, 10);

        $this->assertSame('http://coub.com/view/557l1v80', $x->getValue(Coub::PLAINTEXT));
        $this->assertSame('http://coub.com/view/557l1v80', $x->getEntryUrl());
        $this->assertSame('http://coub.com/embed/557l1v80', $x->getEmbedUrl());
    }

}