<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\Stream;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Internal\Match\Stream\BaseStream;
use TRegx\CleanRegex\Internal\Match\Stream\IntStream;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;

class IntStreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldDelegateAll()
    {
        // given
        $stream = new IntStream($this->mock('all', 'willReturn', $this->matchesOffset('14')));

        // when
        $all = $stream->all();

        // then
        $this->assertSame([14, 19, 25], $all);
    }

    /**
     * @test
     */
    public function shouldDelegateAll_unmatched()
    {
        // given
        $stream = new IntStream($this->mock('all', 'willReturn', new RawMatchesOffset([[]])));

        // when
        $all = $stream->all();

        // then
        $this->assertSame([], $all);
    }

    /**
     * @test
     */
    public function shouldDelegateFirst()
    {
        // given
        $stream = new IntStream($this->mock('first', 'willReturn', new RawMatchOffset([['192', 1]], 0)));

        // when
        $first = $stream->first();

        // then
        $this->assertSame(192, $first);
    }

    /**
     * @test
     */
    public function shouldGetFirstKey()
    {
        // given
        $stream = new IntStream($this->mock('firstKey', 'willReturn', 123));

        // when
        $firstKey = $stream->firstKey();

        // then
        $this->assertSame(123, $firstKey);
    }

    /**
     * @test
     */
    public function shouldAll_throwForMalformedInteger()
    {
        // given
        $stream = new IntStream($this->mock('all', 'willreturn', $this->matchesOffset('Foo')));

        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse 'Foo', but it is not a valid integer");

        // when
        $stream->all();
    }

    /**
     * @test
     */
    public function shouldFirst_throwForMalformedInteger()
    {
        // given
        $stream = new IntStream($this->mock('first', 'willReturn', new RawMatchOffset([['Foo', 1]], 1)));

        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse 'Foo', but it is not a valid integer");

        // when
        $stream->first();
    }

    private function mock(string $methodName, string $setter, $value): BaseStream
    {
        /** @var BaseStream|MockObject $stream */
        $stream = $this->createMock(BaseStream::class);
        $stream->expects($this->once())->method($methodName)->$setter($value);
        $stream->expects($this->never())->method($this->logicalNot($this->matches($methodName)));
        return $stream;
    }

    private function matchesOffset(string $firstValue): RawMatchesOffset
    {
        return new RawMatchesOffset([[
            [$firstValue, 1],
            ['19', 2],
            ['25', 3],
        ]]);
    }
}
