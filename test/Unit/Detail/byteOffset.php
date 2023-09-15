<?php
namespace Test\Unit\Detail;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;

class byteOffset extends TestCase
{
    public function test()
    {
        $pattern = new Pattern('F.r.');
        $detail = $pattern->first('Ours is the Fury');
        $this->assertSame(12, $detail->byteOffset());
    }
}
