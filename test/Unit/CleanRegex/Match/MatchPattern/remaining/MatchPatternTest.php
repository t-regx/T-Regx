<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\remaining;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use Test\Utils\Internal;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Match\MatchPattern;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        $pattern = new MatchPattern(Internal::pattern('\w+'), 'Nice matching pattern');

        // when
        $remaining = $pattern->remaining(Functions::notEquals('Nice'))->all();

        // then
        $this->assertSame(['matching', 'pattern'], $remaining);
    }

    /**
     * @test
     */
    public function shouldThrow_onInvalidReturnType()
    {
        // given
        $pattern = new MatchPattern(Internal::pattern('Foo'), 'Foo');

        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid remaining() callback return type. Expected bool, but integer (4) given');

        // when
        $pattern->remaining(Functions::constant(4))->all();
    }
}
