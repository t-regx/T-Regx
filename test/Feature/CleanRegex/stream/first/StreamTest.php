<?php
namespace Test\Feature\CleanRegex\stream\first;

use PHPUnit\Framework\TestCase;
use Test\Utils\Stream\ArrayStream;
use TRegx\CleanRegex\Exception\NoSuchStreamElementException;

/**
 * @covers \TRegx\CleanRegex\Match\Stream::first
 */
class StreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetFirst()
    {
        // given
        $stream = ArrayStream::unmatched();
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get the first match, but subject was not matched');
        // when
        $stream->first();
    }
}
