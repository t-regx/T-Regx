<?php
namespace Test\Feature\CleanRegex\Split\splitStart;

use PHPUnit\Framework\TestCase;
use TRegx\Exception\MalformedPatternException;

class PatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSplitStartIntoPieces()
    {
        // when
        $pieces = pattern(', ?')->splitStart('Father, Mother, Maiden, Crone, Warrior, Smith, Stranger', 6);
        // then
        $this->assertSame(['Father', 'Mother', 'Maiden', 'Crone', 'Warrior', 'Smith', 'Stranger'], $pieces);
    }

    /**
     * @test
     */
    public function shouldSplitStartIntoPieces_limitThree()
    {
        // when
        $pieces = pattern(', ?')->splitStart('Father, Mother, Maiden, Crone, Warrior, Smith, Stranger', 3);
        // then
        $this->assertSame(['Father', 'Mother', 'Maiden', 'Crone, Warrior, Smith, Stranger'], $pieces);
    }

    /**
     * @test
     */
    public function shouldSplitIntoPieces_withSeparators()
    {
        // when
        $pieces = pattern('(;)(;) ?')->splitStart('Foo;; Bar;; Cat;; Door;;', 2);
        // then
        $this->assertSame(['Foo', ';', ';', 'Bar', ';', ';', 'Cat;; Door;;'], $pieces);
    }

    /**
     * @test
     */
    public function shouldSplitIntoPieces_withSeparators_emptySeparator()
    {
        // when
        $pieces = pattern('()(;) ?')->splitStart('Foo; Bar; Cat; Door', 2);
        // then
        $this->assertSame(['Foo', '', ';', 'Bar', '', ';', 'Cat; Door'], $pieces);
    }

    /**
     * @test
     */
    public function shouldSplitIntoPieces_withSeparators_unmatchedSeparator()
    {
        // when
        $pieces = pattern('(,)?(;) ?')->splitStart('One,; Two; Three,; Four', 2);
        // then
        $this->assertSame(['One', ',', ';', 'Two', null, ';', 'Three,; Four'], $pieces);
    }

    /**
     * @test
     */
    public function shouldReturnSubject_limit0()
    {
        // when
        $pieces = pattern(' ')->splitStart('We work in Dark, to serve the Light', 0);
        // then
        $this->assertSame(['We work in Dark, to serve the Light'], $pieces);
    }

    /**
     * @test
     */
    public function shouldReturnSubject_onUnmatchedSubject()
    {
        // when
        $pieces = pattern(' ')->splitStart('Bar', 0);
        // then
        $this->assertSame(['Bar'], $pieces);
    }

    /**
     * @test
     */
    public function shouldThrowForMalformedPattern()
    {
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        pattern('+')->splitStart('Foo', 1);
    }

    /**
     * @test
     */
    public function shouldThrowForNegativeSplits()
    {
        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Negative splits: -1');
        // when
        pattern('invalid)')->splitStart('Foo', -1);
    }

    /**
     * @test
     */
    public function shouldThrowForNegativeSplitsMinusTwo()
    {
        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Negative splits: -2');
        // when
        pattern('invalid)')->splitStart('Foo', -2);
    }
}
