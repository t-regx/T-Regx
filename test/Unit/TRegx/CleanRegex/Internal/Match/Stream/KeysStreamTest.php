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
        $switcher = new KeysStream($this->mock('all', 'willReturn', ['a' => 'One', 'b' => 'Two', 'c' => 'Three']));

        // when
        $keys = $switcher->all();

        // then
        $this->assertSame(['a', 'b', 'c'], $keys);
    }

    /**
     * @test
     */
    public function shouldGetFirst()
    {
        // given
        $switcher = new KeysStream($this->mock('firstKey', 'willReturn', 'One'));

        // when
        $first = $switcher->first();

        // then
        $this->assertSame('One', $first);
    }

    /**
     * @test
     */
    public function shouldFirstKey_beAlwaysZero()
    {
        // given
        $switcher = new KeysStream($this->zeroInteraction());

        // when
        $firstKey = $switcher->firstKey();

        // then
        $this->assertSame(0, $firstKey);
    }

    private function mock(string $methodName, string $setter, $value): Stream
    {
        /** @var Stream|MockObject $switcher */
        $switcher = $this->createMock(Stream::class);
        $switcher->expects($this->once())->method($methodName)->$setter($value);
        $switcher->expects($this->never())->method($this->logicalNot($this->matches($methodName)));
        return $switcher;
    }

    private function zeroInteraction(): Stream
    {
        /** @var Stream|MockObject $base */
        $base = $this->createMock(Stream::class);
        $base->expects($this->never())->method($this->anything());
        return $base;
    }
}
