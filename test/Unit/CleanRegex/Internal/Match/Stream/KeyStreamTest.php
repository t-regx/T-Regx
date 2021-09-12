<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\Stream;

use PHPUnit\Framework\TestCase;
use Test\Utils\Impl\AllStream;
use Test\Utils\Impl\FirstKeyStream;
use TRegx\CleanRegex\Internal\Match\Stream\KeyStream;

/**
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\KeyStream
 */
class KeyStreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturn_all()
    {
        // given
        $stream = new KeyStream(new AllStream(['a' => 'One', 'b' => 'Two', 'c' => 'Three']));

        // when
        $keys = $stream->all();

        // then
        $this->assertSame(['a', 'b', 'c'], $keys);
    }

    /**
     * @test
     */
    public function shouldReturn_first()
    {
        // given
        $stream = new KeyStream(new FirstKeyStream('One'));

        // when
        $first = $stream->first();

        // then
        $this->assertSame('One', $first);
    }

    /**
     * @test
     */
    public function shouldReturn_firstKey_beAlwaysZero()
    {
        // given
        $stream = new KeyStream(new FirstKeyStream(123));

        // when
        $firstKey = $stream->firstKey();

        // then
        $this->assertSame(0, $firstKey);
    }
}
