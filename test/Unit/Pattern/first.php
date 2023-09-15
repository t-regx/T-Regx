<?php
namespace Test\Unit\Pattern;

use PHPUnit\Framework\TestCase;
use Regex\NoMatchException;
use Regex\Pattern;
use function Test\Fixture\Functions\catching;

class first extends TestCase
{
    public function test()
    {
        $pattern = new Pattern('F.r.');
        $match = $pattern->first('Ours is the Fury');
        $this->assertSame('Fury', $match->text());
    }

    /**
     * @test
     */
    public function unmatched()
    {
        $pattern = new Pattern('What do we say to the god of Death?');
        catching(fn() => $pattern->first('Not today'))
            ->assertException(NoMatchException::class)
            ->assertMessage('Failed to match the subject.');
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function noSecondCall()
    {
        $pattern = new Pattern('(\d+\d+)+3');
        $pattern->first('123 11111111111111111111 3');
    }
}
