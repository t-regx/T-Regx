<?php
namespace Test\Unit\Matcher;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;

class firstOrNull extends TestCase
{
    public function test()
    {
        $pattern = new Pattern('F.r.');
        $match = $pattern->match('Ours is the Fury')->firstOrNull();
        $this->assertSame('Fury', $match->text());
    }

    /**
     * @test
     */
    public function unmatched()
    {
        $pattern = new Pattern('What do we say to the god of Death?');
        $matcher = $pattern->match('Not today');
        $this->assertNull($matcher->firstOrNull());
    }
}
