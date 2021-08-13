<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\iterator;

use PHPUnit\Framework\TestCase;
use Test\Utils\Internal;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\MatchPattern;

/**
 * @covers \TRegx\CleanRegex\Match\MatchPattern::getIterator
 */
class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGet_iterator()
    {
        // given
        $pattern = new MatchPattern(Internal::pattern("([A-Z])?[a-z']+"), new Subject("Nice matching pattern"));
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
        $pattern = new MatchPattern(Internal::pattern('[A-Z]+'), new Subject('Nice matching pattern'));

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
        $pattern = new MatchPattern(Internal::pattern("[0-9]+"), new Subject('Nice matching pattern'));

        // when
        $iterator = $pattern->getIterator();

        // then
        $this->assertFalse($iterator->valid());
    }
}
