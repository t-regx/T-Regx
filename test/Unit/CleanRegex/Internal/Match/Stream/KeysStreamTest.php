<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\Stream;

use PHPUnit\Framework\TestCase;
use Test\Utils\Impl\AllStream;
use Test\Utils\Impl\FirstKeyStream;
use Test\Utils\Impl\ThrowStream;
use TRegx\CleanRegex\Internal\Match\Stream\KeysStream;

/**
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\KeysStream
 */
class KeysStreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturn_all()
    {
        // given
        $stream = new KeysStream(new AllStream(['a' => 'One', 'b' => 'Two', 'c' => 'Three']));

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
        $stream = new KeysStream(new FirstKeyStream('One'));

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
        $stream = new KeysStream(new ThrowStream());

        // when
        $firstKey = $stream->firstKey();

        // then
        $this->assertSame(0, $firstKey);
    }
}
