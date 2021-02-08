<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\remaining;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\MatchPattern;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        $pattern = new MatchPattern(InternalPattern::standard('([A-Z])?[a-z]+'), 'Nice matching pattern');

        // when
        $first = $pattern
            ->remaining(function (Detail $detail) {
                return strlen($detail) > 4;
            })
            ->all();

        // then
        $this->assertSame(['matching', 'pattern'], $first);
    }

    /**
     * @test
     */
    public function shouldThrow_onInvalidReturnType()
    {
        // given
        $pattern = new MatchPattern(InternalPattern::standard('([A-Z])?[a-z]+'), 'Nice matching pattern');

        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid remaining() callback return type. Expected bool, but integer (4) given');

        // when
        $pattern->remaining(Functions::constant(4))->all();
    }
}
