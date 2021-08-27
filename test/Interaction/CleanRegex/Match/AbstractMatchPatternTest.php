<?php
namespace Test\Interaction\TRegx\CleanRegex\Match;

use PHPUnit\Framework\TestCase;
use Test\Utils\ExactExceptionMessage;
use Test\Utils\Internal;
use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\MatchPattern;

/**
 * @covers \TRegx\CleanRegex\Match\AbstractMatchPattern
 */
class AbstractMatchPatternTest extends TestCase
{
    use ExactExceptionMessage;

    /**
     * @test
     */
    public function test_asInt_all()
    {
        // given
        $pattern = new MatchPattern(Internal::pcre('/\d+/'), new Subject('Foo 1 Bar 34 Lorem 42 Ipsum'));

        // when
        $integers = $pattern->asInt()->all();

        // then
        $this->assertSame([1, 34, 42], $integers);
    }

    /**
     * @test
     */
    public function test_asInt_first()
    {
        // given
        $pattern = new MatchPattern(Internal::pcre('/\d+/'), new Subject('Foo 1 Bar 34 Lorem 42 Ipsum'));

        // when
        $integers = $pattern->asInt()->first();

        // then
        $this->assertSame(1, $integers);
    }

    /**
     * @test
     */
    public function test_asInt_first_consumer()
    {
        // given
        $pattern = new MatchPattern(Internal::pcre('/\d+/'), new Subject('Foo 1 Bar 34 Lorem 42 Ipsum'));

        // when
        $pattern->asInt()->first(function ($argument) {
            $this->assertSame(1, $argument);
        });
    }

    /**
     * @test
     */
    public function shouldThrowOnInvalidInteger_asInt()
    {
        // given
        $pattern = new MatchPattern(Internal::pcre('/\d+s?/'), new Subject('One number 9 large, Two number 45s'));

        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse '45s', but it is not a valid integer in base 10");

        // when
        $pattern->asInt()->all();
    }
}
