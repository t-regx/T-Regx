<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\Stream;

use PHPUnit\Framework\TestCase;
use Test\Utils\Impl\AllStreamBase;
use Test\Utils\Impl\FirstStreamBase;
use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Internal\Match\Stream\MatchIntStream;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;

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
        $stream = new MatchIntStream(new AllStreamBase($this->matchesOffset('14')));

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
        $stream = new MatchIntStream(new AllStreamBase(new RawMatchesOffset([[]])));

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
        $stream = new MatchIntStream(FirstStreamBase::text('192'));

        // when
        $first = $stream->first();

        // then
        $this->assertSame(192, $first);
    }

    /**
     * @test
     */
    public function shouldNotDelegate_firstKey()
    {
        // given
        $stream = new MatchIntStream(FirstStreamBase::text('192'));

        // when
        $key = $stream->firstKey();

        // then
        $this->assertSame(0, $key);
    }

    /**
     * @test
     */
    public function shouldThrow_all_forMalformedInteger()
    {
        // given
        $stream = new MatchIntStream(new AllStreamBase($this->matchesOffset('Foo')));

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
        $stream = new MatchIntStream(FirstStreamBase::text('Foo'));

        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse 'Foo', but it is not a valid integer");

        // when
        $stream->first();
    }

    /**
     * @test
     */
    public function shouldThrow_firstKey_forMalformedInteger()
    {
        // given
        $stream = new MatchIntStream(FirstStreamBase::text('Foo'));

        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse 'Foo', but it is not a valid integer");

        // when
        $stream->firstKey();
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
