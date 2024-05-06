<?php
namespace Test\Unit\_system;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;
use Test\Fixture\HandlerSnapshot;

class _matchErrorHandlerUnalterNoError extends TestCase
{
    private Pattern $pattern;

    /**
     * @before
     */
    public function pattern()
    {
        $this->pattern = new Pattern('test');
    }

    /**
     * @test
     */
    public function first()
    {
        $handler = new HandlerSnapshot();
        $this->pattern->first('test');
        $handler->assertEquals();
    }

    /**
     * @test
     */
    public function search()
    {
        $handler = new HandlerSnapshot();
        $this->pattern->search('test');
        $handler->assertEquals();
    }

    /**
     * @test
     */
    public function match()
    {
        $handler = new HandlerSnapshot();
        $this->pattern->match('test');
        $handler->assertEquals();
    }
}
