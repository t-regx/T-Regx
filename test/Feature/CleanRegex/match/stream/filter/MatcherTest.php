<?php
namespace Test\Feature\CleanRegex\match\stream\filter;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\Matcher
 */
class MatcherTest extends TestCase
{
    /**
     * @test
     */
    public function shouldFilterThrow_onInvalidReturnType()
    {
        // given
        $stream = Pattern::of('Foo')->match('Foo')->stream();
        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid filter() callback return type. Expected bool, but integer (45) given');
        // when
        $stream->filter(Functions::constant(45))->all();
    }

    /**
     * @test
     */
    public function shouldFilterThrow_onInvalidReturnType_first()
    {
        // given
        $stream = Pattern::of('Foo')->match('Foo')->stream();
        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid filter() callback return type. Expected bool, but integer (45) given');
        // when
        $stream->filter(Functions::constant(45))->first();
    }
}
