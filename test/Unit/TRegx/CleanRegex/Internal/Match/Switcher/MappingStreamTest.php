<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\Switcher;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Exception\NoFirstSwitcherException;
use TRegx\CleanRegex\Internal\Match\Switcher\MappingStream;
use TRegx\CleanRegex\Internal\Match\Switcher\Stream;

class MappingStreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldMapAll()
    {
        // given
        $switcher = new MappingStream($this->mock('all', 'willReturn', ['One', 'Two', 'Three']), 'strtoupper');

        // when
        $all = $switcher->all();

        // then
        $this->assertSame(['ONE', 'TWO', 'THREE'], $all);
    }

    /**
     * @test
     */
    public function shouldGetFirst()
    {
        // given
        $switcher = new MappingStream($this->mock('first', 'willReturn', 'One'), function (string $element) {
            $this->assertEquals('One', $element, 'Failed to assert that callback is only called for the first element');
            return 'foo';
        });

        // when
        $first = $switcher->first();

        // then
        $this->assertSame('foo', $first);
    }

    /**
     * @test
     */
    public function shouldGetFirstKey()
    {
        // given
        $switcher = new MappingStream($this->mock('firstKey', 'willReturn', 'foo'), [$this, 'fail']);

        // when
        $firstKey = $switcher->firstKey();

        // then
        $this->assertSame('foo', $firstKey);
    }

    /**
     * @test
     */
    public function shouldFirstThrow()
    {
        // given
        $switcher = new MappingStream($this->mock('first', 'willThrowException', new NoFirstSwitcherException()), 'strtoupper');

        // then
        $this->expectException(NoFirstSwitcherException::class);

        // when
        $switcher->first();
    }

    /**
     * @test
     */
    public function shouldFirstReturnInteger()
    {
        // given
        $switcher = new MappingStream($this->mock('first', 'willReturn', 1), function (int $a) {
            return $a;
        });

        // when
        $first = $switcher->first();

        // then
        $this->assertSame(1, $first);
    }

    private function mock(string $methodName, string $setter, $value): Stream
    {
        /** @var Stream|MockObject $switcher */
        $switcher = $this->createMock(Stream::class);
        $switcher->expects($this->once())->method($methodName)->$setter($value);
        $switcher->expects($this->never())->method($this->logicalNot($this->matches($methodName)));
        return $switcher;
    }
}
