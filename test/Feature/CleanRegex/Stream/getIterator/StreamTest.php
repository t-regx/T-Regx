<?php
namespace Test\Feature\CleanRegex\Stream\getIterator;

use PHPUnit\Framework\TestCase;
use Test\Utils\ArrayStream;

/**
 * @covers \TRegx\CleanRegex\Match\Stream
 * @covers \TRegx\CleanRegex\Internal\Match\StreamTerminal
 */
class StreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetIterator()
    {
        // given
        $stream = ArrayStream::of(['Apple', 'Pear', 'Pulm']);
        // when
        $iterator = $stream->getIterator();
        // then
        $this->assertSame(['Apple', 'Pear', 'Pulm'], \iterator_to_array($iterator));
    }
}