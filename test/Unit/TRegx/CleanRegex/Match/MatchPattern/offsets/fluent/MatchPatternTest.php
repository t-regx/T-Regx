<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\offsets\fluent;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\NoFirstElementFluentException;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Match\MatchPattern;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetFirst()
    {
        // given
        $pattern = $this->getMatchPattern('__ Nice matching pattern');

        // when
        $first = $pattern->offsets()->fluent()->all();

        // then
        $this->assertEquals([3, 8, 17], $first);
    }

    /**
     * @test
     */
    public function shouldThrow_onNotMatchingSubject_fluent()
    {
        // given
        $pattern = $this->getMatchPattern('NOT MATCHING');

        // then
        $this->expectException(NoFirstElementFluentException::class);

        // when
        $pattern->offsets()->fluent()->findFirst(function () {
        })->orThrow();
    }

    private function getMatchPattern($subject): MatchPattern
    {
        return new MatchPattern(InternalPattern::standard("([A-Z])?[a-z']+"), $subject);
    }
}
