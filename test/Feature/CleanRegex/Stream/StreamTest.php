<?php
namespace Test\Feature\CleanRegex\Stream;

use PHPUnit\Framework\TestCase;
use Test\Utils\Stream\ArrayStream;

/**
 * @covers \TRegx\CleanRegex\Match\Stream
 */
class StreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeCountable()
    {
        // given
        $stream = ArrayStream::of(['Neo', 'Cypher', 'Trinity']);
        // when
        $size = count($stream);
        // then
        $this->assertSame(3, $size);
    }

    /**
     * @test
     */
    public function shouldBeIterable()
    {
        // given
        $stream = ArrayStream::of(['Apple', 'Pear', 'Pulm']);
        // when
        $elements = [];
        foreach ($stream as $element) {
            $elements[] = $element;
        }
        // then
        $this->assertSame(['Apple', 'Pear', 'Pulm'], $elements);
    }
}
