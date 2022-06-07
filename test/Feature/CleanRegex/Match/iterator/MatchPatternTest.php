<?php
namespace Test\Feature\CleanRegex\Match\iterator;

use PHPUnit\Framework\TestCase;
use Test\Utils\TestCasePasses;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\MatchPattern
 */
class MatchPatternTest extends TestCase
{
    use TestCasePasses;

    /**
     * @test
     */
    public function shouldIterate()
    {
        // given
        $pattern = Pattern::of('\w+')->match('Nice matching pattern');
        // when
        $values = [];
        foreach ($pattern as $detail) {
            $values[] = $detail->text();
        }
        // then
        $this->assertSame(['Nice', 'matching', 'pattern'], $values);
    }

    /**
     * @test
     */
    public function shouldNotIterateUnmatched()
    {
        // given
        $pattern = Pattern::of('Foo')->match('Bar');
        // when
        foreach ($pattern as $detail) {
            $this->fail();
        }
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldGetIterator()
    {
        // given
        $pattern = Pattern::of("([A-Z])?[a-z']+")->match('Nice matching pattern');
        $matches = ['Nice', 'matching', 'pattern'];
        // when
        $iterator = $pattern->getIterator();
        // then
        foreach ($iterator as $index => $match) {
            // then
            $this->assertSame($matches[$index], $match->text());
            $this->assertSame($index, $match->index());
            $this->assertSame('Nice matching pattern', $match->subject());
            $this->assertSame(['Nice', 'matching', 'pattern'], $match->all());
        }
    }

    /**
     * @test
     */
    public function should_hasNext_matched()
    {
        // given
        $pattern = Pattern::of('[A-Z]+')->match('Nice matching pattern');
        // when
        $iterator = $pattern->getIterator();
        // then
        $this->assertTrue($iterator->valid());
    }

    /**
     * @test
     */
    public function shouldNot_hasNext_unmatched()
    {
        // given
        $pattern = Pattern::of('[0-9]+')->match('Nice matching pattern');
        // when
        $iterator = $pattern->getIterator();
        // then
        $this->assertFalse($iterator->valid());
    }

}
