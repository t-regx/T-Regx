<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\Stream;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Internal\Match\Stream\BaseStream;
use TRegx\CleanRegex\Internal\Match\Stream\MatchIntStream;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;

/**
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\MatchIntStream
 */
class MatchIntStreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldDelegate_all()
    {
        // given
        $stream = new MatchIntStream($this->mock('all', $this->matchesOffset('14')));

        // when
        $all = $stream->all();

        // then
        $this->assertSame([14, 19, 25], $all);
    }

    /**
     * @test
     */
    public function shouldDelegate_all_unmatched()
    {
        // given
        $stream = new MatchIntStream($this->mock('all', new RawMatchesOffset([[]])));

        // when
        $all = $stream->all();

        // then
        $this->assertSame([], $all);
    }

    /**
     * @test
     */
    public function shouldDelegate_first()
    {
        // given
        $stream = new MatchIntStream($this->mock('first', new RawMatchOffset([['192', 1]], 0)));

        // when
        $first = $stream->first();

        // then
        $this->assertSame(192, $first);
    }

    /**
     * @test
     */
    public function shouldDelegate_firstKey()
    {
        // given
        $stream = new MatchIntStream($this->mock('firstKey', 123));

        // when
        $firstKey = $stream->firstKey();

        // then
        $this->assertSame(123, $firstKey);
    }

    /**
     * @test
     */
    public function shouldThrow_all_forMalformedInteger()
    {
        // given
        $stream = new MatchIntStream($this->mock('all', $this->matchesOffset('Foo')));

        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse 'Foo', but it is not a valid integer");

        // when
        $stream->all();
    }

    /**
     * @test
     */
    public function shouldThrow_first_forMalformedInteger()
    {
        // given
        $stream = new MatchIntStream($this->mock('first', new RawMatchOffset([['Foo', 1]], 1)));

        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse 'Foo', but it is not a valid integer");

        // when
        $stream->first();
    }

    private function mock(string $methodName, $value): BaseStream
    {
        /** @var BaseStream|MockObject $stream */
        $stream = $this->createMock(BaseStream::class);
        $stream->expects($this->once())->method($methodName)->willReturn($value);
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
