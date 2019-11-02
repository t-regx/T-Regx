<?php
namespace Test\Integration\TRegx\CleanRegex\Match;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\CleanRegex\IntegerFormatException;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Match\MatchPattern;

class AbstractMatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function test_asInt()
    {
        // given
        $pattern = new MatchPattern(new InternalPattern('\d+'), 'Foo 1 Bar 34 Lorem 42 Ipsum');

        // when
        $integers = $pattern->asInt();

        // then
        $this->assertSame([1, 34, 42], $integers);
    }

    /**
     * @test
     */
    public function shouldThrowOnInvalidInteger_asInt()
    {
        // given
        $pattern = new MatchPattern(new InternalPattern('\d+s?'), 'One number 9 large, Two number 45s');

        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse '45s', but it is not a valid integer");

        // when
        $pattern->asInt();
    }
}
