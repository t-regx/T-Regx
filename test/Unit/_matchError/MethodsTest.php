<?php
namespace Test\Unit\_matchError;

use PHPUnit\Framework\TestCase;
use Regex\MatchException;
use Regex\Pattern;

class MethodsTest extends TestCase
{
    private Pattern $pattern;

    /**
     * @before
     */
    public function pattern()
    {
        $this->pattern = new Pattern('(*NO_JIT)(*LIMIT_RECURSION=3)((((motive))))');
    }

    /**
     * @test
     */
    public function test(): void
    {
        $this->expectException(MatchException::class);
        $this->pattern->test('A man with no motive is a man no one suspects.');
    }

    /**
     * @test
     */
    public function search(): void
    {
        $this->expectException(MatchException::class);
        $this->pattern->search('A man with no motive is a man no one suspects.');
    }

    /**
     * @test
     */
    public function matchPartial(): void
    {
        $this->expectException(MatchException::class);
        \iterator_to_array($this->pattern->matchPartial('A man with no motive is a man no one suspects.'));
    }

    /**
     * @test
     */
    public function split(): void
    {
        $this->expectException(MatchException::class);
        $this->pattern->split('A man with no motive is a man no one suspects.');
    }

    /**
     * @test
     */
    public function replace(): void
    {
        $this->expectException(MatchException::class);
        $this->pattern->replace('A man with no motive is a man no one suspects.', 'replacement');
    }

    /**
     * @test
     */
    public function count_(): void
    {
        $this->expectException(MatchException::class);
        $this->pattern->count('A man with no motive is a man no one suspects.');
    }

    /**
     * @test
     */
    public function first(): void
    {
        $this->expectException(MatchException::class);
        $this->pattern->first('A man with no motive is a man no one suspects.');
    }

    /**
     * @test
     */
    public function replaceCallback(): void
    {
        $this->expectException(MatchException::class);
        $this->pattern->replaceCallback('A man with no motive is a man no one suspects.', fn() => null);
    }

    /**
     * @test
     */
    public function filter(): void
    {
        $this->expectException(MatchException::class);
        $this->pattern->filter(['A man with no motive is a man no one suspects.']);
    }

    /**
     * @test
     */
    public function reject(): void
    {
        $this->expectException(MatchException::class);
        $this->pattern->reject(['A man with no motive is a man no one suspects.']);
    }
}
