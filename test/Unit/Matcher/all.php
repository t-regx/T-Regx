<?php
namespace Test\Unit\Matcher;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;

class all extends TestCase
{
    /**
     * @test
     */
    public function first()
    {
        $pattern = new Pattern('F.r.');
        [$match] = $pattern->match('Ours is the Fury')->all();
        $this->assertSame('Fury', $match->text());
        $this->assertSame(12, $match->offset());
        $this->assertSame(0, $match->index());
    }

    /**
     * @test
     */
    public function last()
    {
        $pattern = new Pattern('Ours|Fury');
        [$_, $match] = $pattern->match('Ours is the Fury')->all();
        $this->assertSame('Fury', $match->text());
        $this->assertSame(12, $match->offset());
        $this->assertSame(1, $match->index());
    }

    /**
     * @test
     */
    public function unmatched()
    {
        $pattern = new Pattern('What do we say to the god of Death?');
        $matcher = $pattern->match('Not today');
        $this->assertEmpty($matcher->all());
    }

    /**
     * @test
     */
    public function subject()
    {
        $pattern = new Pattern('.*');
        [$match] = $pattern->match('Ours is the Fury')->all();
        $this->assertSame('Ours is the Fury', $match->subject());
    }
}
