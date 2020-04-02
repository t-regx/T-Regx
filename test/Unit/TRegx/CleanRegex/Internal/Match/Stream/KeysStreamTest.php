<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\Stream;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Match\Stream\KeysStream;
use TRegx\CleanRegex\Internal\Match\Stream\Stream;

class KeysStreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetKeys()
    {
        // given
        $stream = new KeysStream($this->mock('all', 'willReturn', ['a' => 'One', 'b' => 'Two', 'c' => 'Three']));

        // when
        $keys = $stream->all();

        // then
        $this->assertSame(['a', 'b', 'c'], $keys);
    }

    /**
     * @test
     */
    public function shouldGetFirst()
    {
        // given
        $stream = new KeysStream($this->mock('firstKey', 'willReturn', 'One'));

        // when
        $first = $stream->first();

        // then
        $this->assertSame('One', $first);
    }

    /**
     * @test
     */
    public function shouldFirstKey_beAlwaysZero()
    {
        // given
        $stream = new KeysStream($this->zeroInteraction());

        // when
        $firstKey = $stream->firstKey();

        // then
        $this->assertSame(0, $firstKey);
    }

    private function mock(string $methodName, string $setter, $value): Stream
    {
        /** @var Stream|MockObject $stream */
        $stream = $this->createMock(Stream::class);
        $stream->expects($this->once())->method($methodName)->$setter($value);
        $stream->expects($this->never())->method($this->logicalNot($this->matches($methodName)));
        return $stream;
    }

    private function zeroInteraction(): Stream
    {
        /** @var Stream|MockObject $base */
        $base = $this->createMock(Stream::class);
        $base->expects($this->never())->method($this->anything());
        return $base;
    }
}
