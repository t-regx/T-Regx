<?php
namespace Test\Feature\CleanRegex\Match\stream\skip;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsSameMatches;
use Test\Utils\CausesBacktracking;
use Test\Utils\DetailFunctions;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\NoSuchStreamElementException;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\Stream
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
        $remaining = pattern('\d+')->match('12, 15, 16, 18, 20')->stream()->skip(2)->all();
        // then
        $this->assertSameMatches([2 => '16', 3 => '18', 4 => '20'], $remaining);
    }

    /**
     * @test
     */
    public function shouldSkipFirstFourStreamElements()
    {
        // when
        $remaining = pattern('\d+')->match('12, 15, 16, 18, 20')->stream()->skip(4)->all();
        // then
        $this->assertSameMatches([4 => '20'], $remaining);
    }

    /**
     * @test
     */
    public function shouldSkipZeroElements()
    {
        // when
        $remaining = pattern('\d+')->match('12, 15, 16')->stream()->skip(0)->all();
        // then
        $this->assertSameMatches(['12', '15', '16'], $remaining);
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
        pattern('\d+')->match('12, 15, 16')->stream()->skip(-1);
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
        pattern('\d+')->match('12, 15, 16')->stream()->skip(-3);
    }

    /**
     * @test
     */
    public function shouldSkipTwoElementsInTousand()
    {
        // when
        $count = pattern('Foo')->match('Foo')
            ->stream()
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
        $empty = pattern('Foo')->match('Bar')->stream()->skip(0)->all();
        // then
        $this->assertSame([], $empty);
    }

    /**
     * @test
     */
    public function shouldReturnEmptyStreamForOverflowingSkip()
    {
        // when
        $empty = pattern('Foo')->match('Foo,Foo')->stream()->skip(10)->all();
        // then
        $this->assertSame([], $empty);
    }

    /**
     * @test
     */
    public function shouldSkipKeys()
    {
        // when
        $remainingKeys = pattern('\d+')->match('12, 15, 16, 18, 20')->stream()->skip(2)->keys()->all();
        // then
        $this->assertSame([2, 3, 4], $remainingKeys);
    }

    /**
     * @test
     */
    public function shouldGetFirstNoneSkippedFirst()
    {
        // when
        $first = pattern('\d+')->match('12, 15, 16, 18, 20')->stream()->skip(0)->first();
        // then
        $this->assertSame(12, $first->toInt());
    }

    /**
     * @test
     */
    public function shouldGetSecondOneSkippedFirst()
    {
        // when
        $first = pattern('\d+')->match('12, 15, 16, 18, 20')->stream()->skip(1)->first();
        // then
        $this->assertSame(15, $first->toInt());
    }

    /**
     * @test
     */
    public function shouldGetFourthThreeSkippedFirst()
    {
        // when
        $first = pattern('\d+')->match('12, 15, 16, 18, 20')->stream()->skip(3)->first();
        // then
        $this->assertSame(18, $first->toInt());
    }

    /**
     * @test
     */
    public function shouldNotCauseCatastrophicBacktracking()
    {
        // when
        $first = $this->backtrackingMatch()->stream()->skip(0)->first();
        // then
        $this->assertSame('123', $first->text());
    }

    /**
     * @test
     */
    public function shouldNotCauseCatastrophicBacktrackingKeys()
    {
        // when
        $first = $this->backtrackingMatch()->stream()->skip(0)->keys()->first();
        // then
        $this->assertSame(0, $first);
    }

    /**
     * @test
     */
    public function shouldNotCauseCatastrophicBacktrackingKeysassoc()
    {
        // when
        $first = $this->backtrackingMatch()->stream()
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
        $firstKey = pattern('\d+')->match('12, 15, 16, 18, 20')->stream()->skip(0)->keys()->first();
        // then
        $this->assertSame(0, $firstKey);
    }

    /**
     * @test
     */
    public function shouldGetFirstKeyThree()
    {
        // when
        $firstKey = pattern('\d+')->match('12, 15, 16, 18, 20')->stream()->skip(3)->keys()->first();
        // then
        $this->assertSame(3, $firstKey);
    }

    /**
     * @test
     */
    public function shouldGetFirstKeySecondAssoc()
    {
        // when
        $firstKey = pattern('One')->match('One')
            ->stream()
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
        $firstKey = pattern('One')->match('One')
            ->stream()
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
        $firstKey = pattern('One')->match('One')
            ->stream()
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
        pattern('Foo')->match('Bar')->stream()->skip(1)->first();
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
        pattern('Foo')->match('Bar')->stream()->skip(1)->keys()->first();
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
        pattern('Foo')->match('Foo')->stream()->skip(2)->first();
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
        pattern('Foo')->match('Foo')->stream()->skip(2)->keys()->first();
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
        Pattern::of('Foo|Bar')->match('Foo, Bar')->stream()->map(DetailFunctions::text())->skip(2)->first();
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
        pattern('Foo')->match('Foo')->stream()->skip(2)->keys()->first();
    }

    /**
     * @test
     */
    public function shouldNotCallFirstTwice()
    {
        // when
        pattern('\w+')->match('Foo, Bar, Cat')
            ->stream()
            ->map(DetailFunctions::text())
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
        pattern('\w+')->match('Foo, Bar, Cat')
            ->stream()
            ->map(DetailFunctions::text())
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
        $second = Pattern::of('Foo|Bar')->match('Foo, Bar')->stream()->map(DetailFunctions::text())->skip(1)->first();
        // then
        $this->assertSame('Bar', $second);
    }
}
