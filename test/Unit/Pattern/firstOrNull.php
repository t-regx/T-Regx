<?php
namespace Test\Unit\Pattern;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;

class firstOrNull extends TestCase
{
    public function test()
    {
        $pattern = new Pattern('F.r.');
        $match = $pattern->firstOrNull('Ours is the Fury');
        $this->assertSame('Fury', $match->text());
    }

    /**
     * @test
     */
    public function unmatched()
    {
        $pattern = new Pattern('What do we say to the god of Death?');
        $this->assertNull($pattern->firstOrNull('Not today'));
    }
}
