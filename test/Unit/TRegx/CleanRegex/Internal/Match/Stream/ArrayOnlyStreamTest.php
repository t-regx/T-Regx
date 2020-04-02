<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\Stream;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Exception\NoFirstSwitcherException;
use TRegx\CleanRegex\Internal\Match\Stream\ArrayOnlyStream;
use TRegx\CleanRegex\Internal\Match\Stream\Stream;

class ArrayOnlyStreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        $switcher = new ArrayOnlyStream($this->mock('all', 'willReturn', [10 => 'One', 20 => 'Two', 30 => 'Three']), 'array_values');

        // when
        $all = $switcher->all();

        // then
        $this->assertSame(['One', 'Two', 'Three'], $all);
    }

    /**
     * @test
     */
    public function shouldGetFirst()
    {
        // given
        $switcher = new ArrayOnlyStream($this->mock('first', 'willReturn', 'One'), 'strtoupper');

        // when
        $first = $switcher->first();

        // then
        $this->assertSame('One', $first);
    }

    /**
     * @test
     */
    public function shouldGetFirstKey()
    {
        // given
        $switcher = new ArrayOnlyStream($this->mock('firstKey', 'willReturn', 'foo'), [$this, 'fail']);

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
        $switcher = new ArrayOnlyStream($this->mock('first', 'willThrowException', new NoFirstSwitcherException()), 'strlen');

        // then
        $this->expectException(NoFirstSwitcherException::class);

        // when
        $switcher->first();
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
