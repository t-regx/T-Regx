<?php
namespace Test\Unit\Matcher;

use PHPUnit\Framework\TestCase;
use Regex\NoMatchException;
use Regex\Pattern;
use function Test\Fixture\Functions\catching;

class first extends TestCase
{
    public function test()
    {
        $pattern = new Pattern('F.r.');
        $match = $pattern->match('Ours is the Fury')->first();
        $this->assertSame('Fury', $match->text());
    }

    /**
     * @test
     */
    public function unmatched()
    {
        $pattern = new Pattern('What do we say to the god of Death?');
        $matcher = $pattern->match('Not today');
        catching(fn() => $matcher->first())
            ->assertException(NoMatchException::class)
            ->assertMessage('Failed to match the subject.');
    }
}
