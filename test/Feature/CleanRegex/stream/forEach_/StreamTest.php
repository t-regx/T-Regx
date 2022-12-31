<?php
namespace Test\Feature\CleanRegex\stream\forEach_;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use Test\Utils\Stream\ArrayStream;

/**
 * @covers \TRegx\CleanRegex\Match\Stream
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\StreamTerminal
 */
class StreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldIterate()
    {
        // given
        $stream = ArrayStream::of(['One', 'Two', 'Three']);
        // when
        $stream->forEach(Functions::collect($result));
        // then
        $this->assertSame(['One', 'Two', 'Three'], $result);
    }
}
