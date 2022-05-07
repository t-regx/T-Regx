<?php
namespace Test\Feature\TRegx\CleanRegex\Match\stream\limit;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Exception\NoSuchStreamElementException;
use TRegx\CleanRegex\Match\Stream;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;

/**
 * @covers \TRegx\CleanRegex\Match\Stream
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\LimitStream
 */
class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldLimit()
    {
        // when
        $all = pattern('\d+')->match('12, 15, 16, 19, 20')
            ->stream()
            ->asInt()
            ->limit(3)
            ->all();
        // then
        $this->assertSame([12, 15, 16], $all);
    }

    /**
     * @test
     */
    public function shouldLimitAssoc()
    {
        // when
        $all = pattern('Foo')->match('Foo')
            ->stream()
            ->flatMapAssoc(Functions::constant([14 => 'one', 18 => 'two']))
            ->limit(2)
            ->all();
        // then
        $this->assertSame([14 => 'one', 18 => 'two'], $all);
    }

    /**
     * @test
     */
    public function shouldLimitUnderflow()
    {
        // when
        $all = pattern('\d+')->match('12, 15')
            ->stream()
            ->asInt()
            ->limit(4)
            ->all();
        // then
        $this->assertSame([12, 15], $all);
    }

    /**
     * @test
     */
    public function shouldLimitUnmatched()
    {
        // when
        $empty = pattern('Foo')->match('Bar')->stream()->limit(4)->all();
        // then
        $this->assertSame([], $empty);
    }

    /**
     * @test
     */
    public function shouldLimitEmpty()
    {
        // when
        $empty = $this->emptyStream()->limit(5)->all();
        // then
        $this->assertSame([], $empty);
    }

    /**
     * @test
     */
    public function shouldThrowForNegativeOffsetOne()
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Negative limit: -1');
        // when
        pattern('\d+')->match('12, 15, 16')->stream()->limit(-1);
    }

    /**
     * @test
     */
    public function shouldThrowForNegativeOffsetThree()
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Negative limit: -3');
        // when
        pattern('\d+')->match('12, 15, 16')->stream()->limit(-3);
    }

    /**
     * @test
     */
    public function shouldGetFirstLimitOne()
    {
        // when
        $first = pattern('Foo')->match('Foo')->stream()->limit(1)->first();
        // then
        $this->assertSame('Foo', $first->text());
    }

    /**
     * @test
     */
    public function shouldGetFirstLimitThree()
    {
        // when
        $first = pattern('Foo')->match('Foo,Foo')->stream()->limit(3)->first();
        // then
        $this->assertSame('Foo', $first->text());
    }

    /**
     * @test
     */
    public function shouldNotGetFirstUnmatched()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get the first match, but subject was not matched');
        // when
        pattern('Foo')->match('Bar')->stream()->limit(2)->first();
    }

    /**
     * @test
     */
    public function shouldNotGetFirstEmptyLimitOne()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get the first stream element, but the stream has 0 element(s)');
        // when
        $this->emptyStream()->limit(1)->first();
    }

    /**
     * @test
     */
    public function shouldNotGetFirstEmptyLimitFour()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get the first stream element, but the stream has 0 element(s)');
        // when
        $this->emptyStream()->limit(4)->first();
    }

    /**
     * @test
     */
    public function shouldNotGetFirstLimitZero()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get the first stream element, but the stream has 0 element(s)');
        // when
        pattern('Foo')->match('Foo')->stream()->limit(0)->first();
    }

    /**
     * @test
     */
    public function shouldGetFirstKey()
    {
        // when
        $firstKey = pattern('Foo')->match('Foo,Foo')->stream()->limit(3)->keys()->first();
        // then
        $this->assertSame(0, $firstKey);
    }

    /**
     * @test
     */
    public function shouldGetFirstKeyAssoc()
    {
        // when
        $firstKey = pattern('Foo')->match('Foo')
            ->stream()
            ->flatMapAssoc(Functions::constant(['one' => 'one', 'two' => 'two']))
            ->limit(2)
            ->keys()
            ->first();
        // then
        $this->assertSame('one', $firstKey);
    }

    /**
     * @test
     */
    public function shouldNotGetFirstKeyUnmatched()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get the first match, but subject was not matched');
        // when
        pattern('Foo')->match('Bar')->stream()->limit(2)->keys()->first();
    }

    /**
     * @test
     */
    public function shouldNotGetFirstKeyUnmatchedLimitZero()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get the first match, but subject was not matched');
        // when
        pattern('Foo')->match('Bar')->stream()->limit(0)->keys()->first();
    }

    /**
     * @test
     */
    public function shouldNotGetFirstKeyEmptyLimitOne()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get the first stream element, but the stream has 0 element(s)');
        // when
        $this->emptyStream()->limit(1)->keys()->first();
    }

    /**
     * @test
     */
    public function shouldNotGetFirstKeyEmptyLimitFour()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get the first stream element, but the stream has 0 element(s)');
        // when
        $this->emptyStream()->limit(4)->keys()->first();
    }

    /**
     * @test
     */
    public function shouldNotGetFirstKeyLimitZero()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get the first stream element, but the stream has 0 element(s)');
        // when
        pattern('Foo')->match('Foo')->stream()->limit(0)->keys()->first();
    }

    private function emptyStream(): Stream
    {
        return pattern('Foo')->match('Foo')->stream()->filter(Functions::constant(false));
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidPattern()
    {
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        Pattern::of('+')->match('Foo')->stream()->limit(2)->all();
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidPatternFirst()
    {
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        Pattern::of('+')->match('Foo')->stream()->limit(2)->first();
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidPatternLimitZero()
    {
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        Pattern::of('+')->match('Foo')->stream()->limit(0)->first();
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidPatternFirstLimitZero()
    {
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        Pattern::of('+')->match('Foo')->stream()->limit(0)->first();
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidPatternFirstKeyLimitZero()
    {
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        Pattern::of('+')->match('Foo')->stream()->limit(0)->keys()->first();
    }

    /**
     * @test
     */
    public function shouldThrowForMissingGroupAllLimitZero()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage('Nonexistent group: #2');
        // when
        Pattern::of('(Foo)')->match('Foo')->group(2)->stream()->limit(0)->all();
    }

    /**
     * @test
     */
    public function shouldThrowForMissingGroupFirstLimitZero()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage('Nonexistent group: #2');
        // when
        Pattern::of('(Foo)')->match('Foo')->group(2)->stream()->limit(0)->first();
    }

    /**
     * @test
     */
    public function shouldThrowForMissingGroupFirstKeyLimitZero()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage('Nonexistent group: #2');
        // when
        Pattern::of('(Foo)')->match('Foo')->group(2)->stream()->limit(0)->keys()->first();
    }

    /**
     * @test
     */
    public function shouldThrowForMissingGroupAllLimitZeroUnmatched()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage('Nonexistent group: #2');
        // when
        Pattern::of('(Foo)')->match('Bar')->group(2)->stream()->limit(0)->all();
    }

    /**
     * @test
     */
    public function shouldThrowForMissingGroupFirstLimitZeroUnmatched()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage('Nonexistent group: #2');
        // when
        Pattern::of('(Foo)')->match('Bar')->group(2)->stream()->limit(0)->first();
    }

    /**
     * @test
     */
    public function shouldThrowForMissingGroupFirstKeyLimitZeroUnmatched()
    {
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage('Nonexistent group: #2');
        // when
        Pattern::of('(Foo)')->match('Bar')->group(2)->stream()->limit(0)->keys()->first();
    }
}
