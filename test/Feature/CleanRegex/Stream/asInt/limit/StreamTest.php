<?php
namespace Test\Feature\CleanRegex\Stream\asInt\limit;

use PHPUnit\Framework\TestCase;
use Test\Utils\ArrayStream;
use TRegx\CleanRegex\Exception\IntegerFormatException;

/**
 * @coversNothing
 */
class StreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldThrowForMalformedInteger_whenLimited()
    {
        // given
        $stream = ArrayStream::of(['Foo']);
        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse stream element 'Foo', but it is not a valid integer in base 10");
        // when
        $stream->asInt()->limit(0)->first();
    }
}
