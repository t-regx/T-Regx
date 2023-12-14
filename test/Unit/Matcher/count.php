<?php
namespace Test\Unit\Matcher;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;

class count extends TestCase
{
    public function test()
    {
        $lowercaseWords = new Pattern('\b[a-z]+');
        $matcher = $lowercaseWords->match('Fear cuts deeper than swords');
        $this->assertSame(4, \count($matcher));
    }

    /**
     * @test
     */
    public function unmatched()
    {
        $pattern = new Pattern('Chaos is a pit');
        $matcher = $pattern->match('Chaos is a ladder');
        $this->assertSame(0, \count($matcher));
    }
}
