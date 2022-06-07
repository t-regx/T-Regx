<?php
namespace Test\Feature\CleanRegex\Stream\skip;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\ArrayStream;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\NoSuchStreamElementException;

/**
 * @covers \TRegx\CleanRegex\Match\Stream
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\SkipStream
 */
class StreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSkipFirstTwoStreamElements()
    {
        // when
        $remaining = ArrayStream::of(['12', '15', '16', '18', '20'])->skip(2)->all();
        // then
        $this->assertSame([2 => '16', 3 => '18', 4 => '20'], $remaining);
    }

    /**
     * @test
     */
    public function shouldSkipFirstFourStreamElements()
    {
        // when
        $remaining = ArrayStream::of(['12', '15', '16', '18', '20'])->skip(4)->all();
        // then
        $this->assertSame([4 => '20'], $remaining);
    }

    /**
     * @test
     */
    public function shouldSkipZeroElements()
    {
        // when
        $remaining = ArrayStream::of(['12', '15', '16'])->skip(0)->all();
        // then
        $this->assertSame(['12', '15', '16'], $remaining);
    }

    /**
     * @test
     */
    public function shouldThrowForNegativeOffsetOne()
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Negative offset: -1');
        // when
        ArrayStream::of(['12', '15', '16'])->skip(-1);
    }

    /**
     * @test
     */
    public function shouldThrowForNegativeOffsetThree()
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Negative offset: -3');
        // when
        ArrayStream::of(['12', '15', '16'])->skip(-3);
    }

    /**
     * @test
     */
    public function shouldSkipTwoElementsInTousand()
    {
        // given
        $thousand = ArrayStream::of(\array_fill(0, 1000, null));
        // when
        $count = $thousand->skip(2)->count();
        // then
        $this->assertSame(998, $count);
    }

    /**
     * @test
     */
    public function shouldSkipEmptyStream()
    {
        // when
        $empty = ArrayStream::unmatched()->skip(0)->all();
        // then
        $this->assertSame([], $empty);
    }

    /**
     * @test
     */
    public function shouldReturnEmptyStreamForOverflowingSkip()
    {
        // when
        $empty = ArrayStream::of(['Foo', 'Foo'])->skip(10)->all();
        // then
        $this->assertSame([], $empty);
    }

    /**
     * @test
     */
    public function shouldSkipKeys()
    {
        // when
        $remainingKeys = ArrayStream::of(['12', '15', '16', '18', '20'])->skip(2)->keys()->all();
        // then
        $this->assertSame([2, 3, 4], $remainingKeys);
    }

    /**
     * @test
     */
    public function shouldGetFirstNoneSkippedFirst()
    {
        // when
        $first = ArrayStream::of(['12', '15', '16', '18', '20'])->skip(0)->first();
        // then
        $this->assertSame('12', $first);
    }

    /**
     * @test
     */
    public function shouldGetSecondOneSkippedFirst()
    {
        // when
        $first = ArrayStream::of(['12', '15', '16', '18', '20'])->skip(1)->first();
        // then
        $this->assertSame('15', $first);
    }

    /**
     * @test
     */
    public function shouldGetFourthThreeSkippedFirst()
    {
        // when
        $first = ArrayStream::of(['12', '15', '16', '18', '20'])->skip(3)->first();
        // then
        $this->assertSame('18', $first);
    }

    /**
     * @test
     */
    public function shouldGetFirstKeyZero()
    {
        // when
        $firstKey = ArrayStream::of(['12', '15', '16', '18', '20'])->skip(0)->keys()->first();
        // then
        $this->assertSame(0, $firstKey);
    }

    /**
     * @test
     */
    public function shouldGetFirstKeyThree()
    {
        // when
        $firstKey = ArrayStream::of(['12', '15', '16', '18', '20'])->skip(3)->keys()->first();
        // then
        $this->assertSame(3, $firstKey);
    }

    /**
     * @test
     */
    public function shouldGetFirstKeySecondAssoc()
    {
        // given
        $stream = ArrayStream::of([
            'One'   => 1,
            'Two'   => 1,
            'Three' => 1,
        ]);
        // when
        $firstKey = $stream->skip(2)->keys()->first();
        // then
        $this->assertSame('Three', $firstKey);
    }

    /**
     * @test
     */
    public function shouldGetFirstSecondAssocIntegerKeys()
    {
        // given
        $stream = ArrayStream::of([
            10 => 'One',
            11 => 'Two',
            12 => 'Three',
        ]);
        // when
        $firstKey = $stream->skip(2)->first();
        // then
        $this->assertSame('Three', $firstKey);
    }

    /**
     * @test
     */
    public function shouldGetFirstSecondAssocStringKeys()
    {
        // given
        $stream = ArrayStream::of([
            'One'   => 'One',
            'Two'   => 'Two',
            'Three' => 'Three',
        ]);
        // when
        $firstKey = $stream->skip(2)->first();
        // then
        $this->assertSame('Three', $firstKey);
    }

    /**
     * @test
     */
    public function shouldSkipOneFirstUnmatched()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get the first stream element, but the stream has 0 element(s)');
        // when
        ArrayStream::unmatched()->skip(1)->first();
    }

    /**
     * @test
     */
    public function shouldSkipOneFirstUnmatchedKeys()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get the first stream element, but the stream has 0 element(s)');
        // when
        ArrayStream::unmatched()->skip(1)->keys()->first();
    }

    /**
     * @test
     */
    public function shouldSkipTwoFirstMatched()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get the first stream element, but the stream has 0 element(s)');
        // when
        ArrayStream::empty()->skip(2)->first();
    }

    /**
     * @test
     */
    public function shouldSkipTwoFirstMatchedKeys()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get the first stream element, but the stream has 0 element(s)');
        // when
        ArrayStream::of(['Foo'])->skip(2)->keys()->first();
    }

    /**
     * @test
     */
    public function shouldSkipTwoMatchedTwo()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get the first stream element, but the stream has 0 element(s)');
        // when
        ArrayStream::of(['Foo', 'Bar'])->skip(2)->first();
    }

    /**
     * @test
     */
    public function shouldSkipOneFirstMatchedKeys()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get the first stream element, but the stream has 0 element(s)');
        // when
        ArrayStream::of(['Foo'])->skip(2)->keys()->first();
    }

    /**
     * @test
     */
    public function shouldNotCallFirstTwice()
    {
        // when
        ArrayStream::of(['Foo', 'Bar', 'Cat'])
            ->map(Functions::collect($texts))
            ->skip(2)
            ->first();
        // then
        $this->assertSame(['Foo', 'Bar', 'Cat'], $texts);
    }

    /**
     * @test
     */
    public function shouldNotCallFirstTwiceKeys()
    {
        // when
        ArrayStream::of(['Foo', 'Bar', 'Cat'])
            ->map(Functions::collect($texts))
            ->skip(2)
            ->keys()
            ->first();
        // then
        $this->assertSame(['Foo', 'Bar', 'Cat'], $texts);
    }

    /**
     * @test
     */
    public function shouldGetSecondSkippedFirst()
    {
        // when
        $second = ArrayStream::of(['Foo', 'Bar'])->skip(1)->first();
        // then
        $this->assertSame('Bar', $second);
    }
}
