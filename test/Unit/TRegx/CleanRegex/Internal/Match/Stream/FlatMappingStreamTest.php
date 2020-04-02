<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\Stream;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Internal\Exception\NoFirstSwitcherException;
use TRegx\CleanRegex\Internal\Match\Stream\FlatMappingStream;
use TRegx\CleanRegex\Internal\Match\Stream\Stream;

class FlatMappingStreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        $switcher = new FlatMappingStream($this->mock('all', 'willReturn', ['One', 'Two', 'Three']), 'str_split');

        // when
        $all = $switcher->all();

        // then
        $this->assertSame(['O', 'n', 'e', 'T', 'w', 'o', 'T', 'h', 'r', 'e', 'e'], $all);
    }

    /**
     * @test
     */
    public function shouldGetFirst()
    {
        // given
        $switcher = new FlatMappingStream($this->mock('first', 'willReturn', 'One'), 'str_split');

        // when
        $first = $switcher->first();

        // then
        $this->assertSame(['O', 'n', 'e'], $first);
    }

    /**
     * @test
     */
    public function shouldGetFirstKey()
    {
        // given
        $switcher = new FlatMappingStream($this->mock('firstKey', 'willReturn', 'foo'), [$this, 'fail']);

        // when
        $firstKey = $switcher->firstKey();

        // then
        $this->assertSame('foo', $firstKey);
    }

    /**
     * @test
     */
    public function shouldFirstThrow_forNoFirstElement()
    {
        // given
        $switcher = new FlatMappingStream($this->mock('first', 'willThrowException', new NoFirstSwitcherException()), 'strlen');

        // then
        $this->expectException(NoFirstSwitcherException::class);

        // when
        $switcher->first();
    }

    /**
     * @test
     */
    public function shouldReturn_forEmptyArray()
    {
        // given
        $switcher = new FlatMappingStream($this->mock('first', 'willReturn', []), function ($a) {
            return $a;
        });

        // when
        $first = $switcher->first();

        // then
        $this->assertSame([], $first);
    }

    /**
     * @test
     */
    public function shouldFirstThrow_invalidReturnType()
    {
        // given
        $switcher = new FlatMappingStream($this->mock('first', 'willReturn', 'Foo'), 'strlen');

        // then
        $this->expectException(InvalidReturnValueException::class);

        // when
        $switcher->first();
    }

    /**
     * @test
     */
    public function shouldAllThrow_invalidReturnType()
    {
        // given
        $switcher = new FlatMappingStream($this->mock('all', 'willReturn', ['Foo']), 'strlen');

        // then
        $this->expectException(InvalidReturnValueException::class);

        // when
        $switcher->all();
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
