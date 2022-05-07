<?php
namespace Test\Feature\TRegx\CleanRegex\Match\stream\asInt\skip;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsSameMatches;
use Test\Utils\CausesBacktracking;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Exception\NoSuchStreamElementException;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\IntStream
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\SkipStream
 */
class MatchPatternTest extends TestCase
{
    use AssertsSameMatches, CausesBacktracking;

    /**
     * @test
     */
    public function shouldSkipFirstTwoStreamElements()
    {
        // when
        $remaining = pattern('\d+')->match('12, 15, 16, 18, 20')->asInt()->skip(2)->all();
        // then
        $this->assertSameMatches([2 => 16, 3 => 18, 4 => 20], $remaining);
    }

    /**
     * @test
     */
    public function shouldSkipFirstFourStreamElements()
    {
        // when
        $remaining = pattern('\d+')->match('12, 15, 16, 18, 20')->asInt()->skip(4)->all();
        // then
        $this->assertSameMatches([4 => 20], $remaining);
    }

    /**
     * @test
     */
    public function shouldSkipZeroElements()
    {
        // when
        $remaining = pattern('\d+')->match('12, 15, 16')->asInt()->skip(0)->all();
        // then
        $this->assertSameMatches([12, 15, 16], $remaining);
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
        pattern('\d+')->match('12, 15, 16')->asInt()->skip(-1);
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
        pattern('\d+')->match('12, 15, 16')->asInt()->skip(-3);
    }

    /**
     * @test
     */
    public function shouldSkipTwoElementsInTousand()
    {
        // when
        $count = pattern('13')->match('13')
            ->asInt()
            ->flatMap(Functions::arrayOfSize(1000, []))
            ->skip(2)
            ->count();
        // then
        $this->assertSame(998, $count);
    }

    /**
     * @test
     */
    public function shouldSkipEmptyStream()
    {
        // when
        $empty = pattern('Foo')->match('Bar')->asInt()->skip(0)->all();
        // then
        $this->assertSame([], $empty);
    }

    /**
     * @test
     */
    public function shouldThrowForMalformedIntegerEvenWhenSkipped()
    {
        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse 'Foo', but it is not a valid integer in base 10");
        // when
        pattern('Foo')->match('Foo,Foo')->asInt()->skip(10)->all();
    }

    /**
     * @test
     */
    public function shouldReturnEmptyStreamForOverflowingSkip()
    {
        // when
        $empty = pattern('12')->match('12,12')->asInt()->skip(10)->all();
        // then
        $this->assertSame([], $empty);
    }

    /**
     * @test
     */
    public function shouldSkipKeys()
    {
        // when
        $remainingKeys = pattern('\d+')->match('12, 15, 16, 18, 20')->asInt()->skip(2)->keys()->all();
        // then
        $this->assertSame([2, 3, 4], $remainingKeys);
    }

    /**
     * @test
     */
    public function shouldGetFirstNoneSkippedFirst()
    {
        // when
        $first = pattern('\d+')->match('12, 15, 16, 18, 20')->asInt()->skip(0)->first();
        // then
        $this->assertSame(12, $first);
    }

    /**
     * @test
     */
    public function shouldGetSecondOneSkippedFirst()
    {
        // when
        $first = pattern('\d+')->match('12, 15, 16, 18, 20')->asInt()->skip(1)->first();
        // then
        $this->assertSame(15, $first);
    }

    /**
     * @test
     */
    public function shouldGetFourthThreeSkippedFirst()
    {
        // when
        $first = pattern('\d+')->match('12, 15, 16, 18, 20')->asInt()->skip(3)->first();
        // then
        $this->assertSame(18, $first);
    }

    /**
     * @test
     */
    public function shouldNotCauseCatastrophicBacktracking()
    {
        // when
        $first = $this->backtrackingMatch()->asInt()->skip(0)->first();
        // then
        $this->assertSame(123, $first);
    }

    /**
     * @test
     */
    public function shouldNotCauseCatastrophicBacktrackingKeys()
    {
        // when
        $first = $this->backtrackingMatch()->asInt()->skip(0)->keys()->first();
        // then
        $this->assertSame(0, $first);
    }

    /**
     * @test
     */
    public function shouldNotCauseCatastrophicBacktrackingKeysassoc()
    {
        // when
        $first = $this->backtrackingMatch()->asInt()
            ->flatMapAssoc(Functions::constant(['key' => 'value']))
            ->skip(0)
            ->keys()
            ->first();
        // then
        $this->assertSame('key', $first);
    }

    /**
     * @test
     */
    public function shouldGetFirstKeyZero()
    {
        // when
        $firstKey = pattern('\d+')->match('12, 15, 16, 18, 20')->asInt()->skip(0)->keys()->first();
        // then
        $this->assertSame(0, $firstKey);
    }

    /**
     * @test
     */
    public function shouldGetFirstKeyThree()
    {
        // when
        $firstKey = pattern('\d+')->match('12, 15, 16, 18, 20')->asInt()->skip(3)->keys()->first();
        // then
        $this->assertSame(3, $firstKey);
    }

    /**
     * @test
     */
    public function shouldGetFirstKeySecondAssoc()
    {
        // when
        $firstKey = pattern('13')->match('13')
            ->asInt()
            ->flatMapAssoc(Functions::constant([
                'One'   => 1,
                'Two'   => 1,
                'Three' => 1,
            ]))
            ->skip(2)
            ->keys()
            ->first();
        // then
        $this->assertSame('Three', $firstKey);
    }

    /**
     * @test
     */
    public function shouldGetFirstSecondAssocIntegerKeys()
    {
        // when
        $firstKey = pattern('54')->match('54')
            ->asInt()
            ->flatMapAssoc(Functions::constant([
                10 => 'One',
                11 => 'Two',
                12 => 'Three',
            ]))
            ->skip(2)
            ->first();
        // then
        $this->assertSame('Three', $firstKey);
    }

    /**
     * @test
     */
    public function shouldGetFirstSecondAssocStringKeys()
    {
        // when
        $firstKey = pattern('65')->match('65')
            ->asInt()
            ->flatMapAssoc(Functions::constant([
                'One'   => 'One',
                'Two'   => 'Two',
                'Three' => 'Three',
            ]))
            ->skip(2)
            ->first();
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
        $this->expectExceptionMessage('Expected to get the first match, but subject was not matched');
        // when
        pattern('Foo')->match('Bar')->asInt()->skip(1)->first();
    }

    /**
     * @test
     */
    public function shouldSkipOneFirstUnmatchedKeys()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get the first match, but subject was not matched');
        // when
        pattern('Foo')->match('Bar')->asInt()->skip(1)->keys()->first();
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
        pattern('12')->match('12')->asInt()->skip(2)->first();
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
        pattern('13')->match('13')->asInt()->skip(2)->keys()->first();
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
        Pattern::of('13|14')->match('13, 14')->asInt()->map(Functions::identity())->skip(2)->first();
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
        pattern('25')->match('25')->asInt()->skip(2)->keys()->first();
    }

    /**
     * @test
     */
    public function shouldNotCallFirstTwice()
    {
        // when
        pattern('\d+')->match('123, 456, 789')
            ->asInt()
            ->map(Functions::collect($texts))
            ->skip(2)
            ->first();
        // then
        $this->assertSame([123, 456, 789], $texts);
    }

    /**
     * @test
     */
    public function shouldNotCallFirstTwiceKeys()
    {
        // when
        pattern('\d+')->match('321, 654, 987')
            ->asInt()
            ->map(Functions::collect($texts))
            ->skip(2)
            ->keys()
            ->first();
        // then
        $this->assertSame([321, 654, 987], $texts);
    }

    /**
     * @test
     */
    public function shouldGetSecondSkippedFirst()
    {
        // when
        $second = Pattern::of('42|69')->match('42, 69')->asInt()->map(Functions::identity())->skip(1)->first();
        // then
        $this->assertSame(69, $second);
    }
}
