<?php
namespace Test\Unit\_system;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;
use Test\Fixture\HandlerSnapshot;
use function Test\Fixture\Functions\catching;

class _matchErrorHandlerUnalter extends TestCase
{
    private Pattern $pattern;

    /**
     * @before
     */
    public function pattern()
    {
        $this->pattern = new Pattern('(?=word\K)');
    }

    /**
     * @test
     */
    public function first()
    {
        $handler = new HandlerSnapshot();
        catching(fn() => $this->pattern->first('word'));
        $handler->assertEquals();
    }

    /**
     * @test
     */
    public function search()
    {
        $handler = new HandlerSnapshot();
        catching(fn() => $this->pattern->search('word'));
        $handler->assertEquals();
    }

    /**
     * @test
     */
    public function match()
    {
        $handler = new HandlerSnapshot();
        catching(fn() => $this->pattern->match('word'));
        $handler->assertEquals();
    }
}
