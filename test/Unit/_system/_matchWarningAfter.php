<?php
namespace Test\Unit\_system;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;
use function Test\Fixture\Functions\catching;

class _matchWarningAfter extends TestCase
{
    private Pattern $pattern;

    /**
     * @before
     */
    public function noWarnings()
    {
        \error_clear_last();
    }

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
    public function first(): void
    {
        catching(fn() => $this->pattern->first('word'));
        $this->assertNull(\error_get_last());
    }

    /**
     * @test
     */
    public function search(): void
    {
        catching(fn() => $this->pattern->search('word'));
        $this->assertNull(\error_get_last());
    }

    /**
     * @test
     */
    public function match(): void
    {
        catching(fn() => $this->pattern->match('word'));
        $this->assertNull(\error_get_last());
    }
}
