<?php
namespace Test\Feature\CleanRegex\Match\stream\asInt\limit;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Exception\NoSuchStreamElementException;
use TRegx\CleanRegex\Match\Stream;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;

/**
 * @covers \TRegx\CleanRegex\Match\IntStream
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
        $all = pattern('98')->match('98')
            ->asInt()
            ->flatMapAssoc(Functions::constant([14 => 'one', 18 => 'two']))
            ->limit(2)
            ->all();
        // then
        $this->assertSame([14 => 'one', 18 => 'two'], $all);
    }

    /**
     * @test
     */
    public function shouldLimitUnmatched()
    {
        // when
        $empty = pattern('Foo')->match('Bar')->asInt()->limit(4)->all();
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
        pattern('\d+')->match('12, 15, 16')->asInt()->limit(-1);
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
        pattern('\d+')->match('12, 15, 16')->asInt()->limit(-3);
    }

    /**
     * @test
     */
    public function shouldGetFirstLimitOne()
    {
        // when
        $first = pattern('54')->match('54')->asInt()->limit(1)->first();
        // then
        $this->assertSame(54, $first);
    }

    /**
     * @test
     */
    public function shouldGetFirstLimitThree()
    {
        // when
        $first = pattern('54')->match('54,54')->asInt()->limit(3)->first();
        // then
        $this->assertSame(54, $first);
    }

    /**
     * @test
     */
    public function shouldNotGetFirstUnmatched()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get the first match as integer, but subject was not matched');
        // when
        pattern('Foo')->match('Bar')->asInt()->limit(2)->first();
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
    public function shouldThrowForMalformedIntegerEvenWhenSkipped()
    {
        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse 'Foo', but it is not a valid integer in base 10");
        // when
        pattern('Foo')->match('Foo')->asInt()->limit(0)->first();
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
        pattern('54')->match('54')->asInt()->limit(0)->first();
    }

    /**
     * @test
     */
    public function shouldGetFirstKey()
    {
        // when
        $firstKey = pattern('54')->match('54,54')->asInt()->limit(3)->keys()->first();
        // then
        $this->assertSame(0, $firstKey);
    }

    /**
     * @test
     */
    public function shouldGetFirstKeyAssoc()
    {
        // when
        $firstKey = pattern('65')->match('65')
            ->asInt()
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
        $this->expectExceptionMessage('Expected to get the first match as integer, but subject was not matched');
        // when
        pattern('Foo')->match('Bar')->asInt()->limit(2)->keys()->first();
    }

    /**
     * @test
     */
    public function shouldNotGetFirstKeyUnmatchedLimitZero()
    {
        // then
        $this->expectException(NoSuchStreamElementException::class);
        $this->expectExceptionMessage('Expected to get the first match as integer, but subject was not matched');
        // when
        pattern('Foo')->match('Bar')->asInt()->limit(0)->keys()->first();
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
        pattern('54')->match('54')->asInt()->limit(0)->keys()->first();
    }

    private function emptyStream(): Stream
    {
        return pattern('14')->match('14')->asInt()->filter(Functions::constant(false));
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
        Pattern::of('+')->match('Foo')->asInt()->limit(2)->all();
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
        Pattern::of('+')->match('Foo')->asInt()->limit(2)->first();
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
        Pattern::of('+')->match('Foo')->asInt()->limit(0)->first();
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
        Pattern::of('+')->match('Foo')->asInt()->limit(0)->first();
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
        Pattern::of('+')->match('Foo')->asInt()->limit(0)->keys()->first();
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
        Pattern::of('(Foo)')->match('Foo')->group(2)->asInt()->limit(0)->all();
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
        Pattern::of('(Foo)')->match('Foo')->group(2)->asInt()->limit(0)->first();
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
        Pattern::of('(Foo)')->match('Foo')->group(2)->asInt()->limit(0)->keys()->first();
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
        Pattern::of('(Foo)')->match('Bar')->group(2)->asInt()->limit(0)->all();
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
        Pattern::of('(Foo)')->match('Bar')->group(2)->asInt()->limit(0)->first();
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
        Pattern::of('(Foo)')->match('Bar')->group(2)->asInt()->limit(0)->keys()->first();
    }
}
