<?php
namespace Test\Feature\CleanRegex\stream\asInt\skip;

use PHPUnit\Framework\TestCase;
use Test\Utils\Stream\ArrayStream;
use TRegx\CleanRegex\Exception\IntegerFormatException;

/**
 * @coversNothing
 */
class StreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldThrowForMalformedInteger_whenSkipped()
    {
        // given
        $stream = ArrayStream::of(['Foo']);
        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse stream element 'Foo', but it is not a valid integer in base 10");
        // when
        $stream->asInt()->skip(0)->first();
    }
}
