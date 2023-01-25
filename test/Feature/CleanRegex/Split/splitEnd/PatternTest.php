<?php
namespace Test\Feature\CleanRegex\Split\splitEnd;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;

class PatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSplitEndIntoPieces_limitEqual()
    {
        // when
        $pieces = Pattern::of(', ?')->splitEnd('Father, Mother, Maiden, Crone, Warrior, Smith, Stranger', 6);
        // then
        $this->assertSame(['Father', 'Mother', 'Maiden', 'Crone', 'Warrior', 'Smith', 'Stranger'], $pieces);
    }

    /**
     * @test
     */
    public function shouldSplitEndIntoPieces_noSplits()
    {
        // when
        $pieces = Pattern::of(', ?')->splitEnd('Bifur, Bofur, Bombur', 0);
        // then
        $this->assertSame(['Bifur, Bofur, Bombur'], $pieces);
    }

    /**
     * @test
     */
    public function shouldSplitEndIntoPieces_singleSplit()
    {
        // when
        $pieces = Pattern::of(', ?')->splitEnd('Bifur, Bofur, Bombur', 1);
        // then
        $this->assertSame(['Bifur, Bofur', 'Bombur'], $pieces);
    }

    /**
     * @test
     */
    public function shouldSplitEndIntoPieces_twoSplits()
    {
        // when
        $pieces = Pattern::of(', ?')->splitEnd('Bifur, Bofur, Bombur', 2);
        // then
        $this->assertSame(['Bifur', 'Bofur', 'Bombur'], $pieces);
    }

    /**
     * @test
     */
    public function firstElementEmpty_limit2()
    {
        // when
        $pieces = Pattern::of(', ')->splitEnd(', Bofur, Bombur', 2);
        // then
        $this->assertSame(['', 'Bofur', 'Bombur'], $pieces);
    }

    /**
     * @test
     */
    public function firstElementEmpty_limit3()
    {
        // when
        $pieces = Pattern::of(', ')->splitEnd(', Bofur, Bombur', 3);
        // then
        $this->assertSame(['', 'Bofur', 'Bombur'], $pieces);
    }

    /**
     * @test
     */
    public function shouldSplitEndIntoPieces_threeSplits()
    {
        // when
        $pieces = Pattern::of(', ?')->splitEnd('Bifur, Bofur, Bombur', 3);
        // then
        $this->assertSame(['Bifur', 'Bofur', 'Bombur'], $pieces);
    }

    /**
     * @test
     */
    public function shouldSplitEndIntoPieces_fourSplits()
    {
        // when
        $pieces = Pattern::of(', ?')->splitEnd('Bifur, Bofur, Bombur', 4);
        // then
        $this->assertSame(['Bifur', 'Bofur', 'Bombur'], $pieces);
    }

    /**
     * @test
     */
    public function shouldSplitEndIntoPieces_limitExceeding()
    {
        // when
        $pieces = Pattern::of(', ?')->splitEnd('Father, Mother, Maiden, Crone, Warrior, Smith, Stranger', 7);
        // then
        $this->assertSame(['Father', 'Mother', 'Maiden', 'Crone', 'Warrior', 'Smith', 'Stranger'], $pieces);
    }

    /**
     * @test
     */
    public function shouldSplitEndIntoPieces_limitThree()
    {
        // when
        $pieces = Pattern::of(', ?')->splitEnd('Father, Mother, Maiden, Crone, Warrior, Smith, Stranger', 3);
        // then
        $this->assertSame(['Father, Mother, Maiden, Crone', 'Warrior', 'Smith', 'Stranger'], $pieces);
    }

    /**
     * @test
     */
    public function shouldSplitEndIntoPieces_withSeparator_limitThree()
    {
        // when
        $pieces = Pattern::of('(,) ?')->splitEnd('Father, Mother, Maiden, Crone, Warrior, Smith, Stranger', 3);
        // then
        $this->assertSame(['Father, Mother, Maiden, Crone', ',', 'Warrior', ',', 'Smith', ',', 'Stranger'], $pieces);
    }

    /**
     * @test
     * @depends shouldSplitEndIntoPieces_limitThree
     */
    public function shouldSplitEndIntoPieces_withSeparators_limitThree()
    {
        // when
        $pieces = Pattern::of('(,)( )?')->splitEnd('Father, Mother, Maiden, Crone, Warrior, Smith, Stranger', 2);
        // then
        $this->assertSame(['Father, Mother, Maiden, Crone, Warrior', ',', ' ', 'Smith', ',', ' ', 'Stranger'], $pieces);
    }

    /**
     * @test
     */
    public function shouldSplitEndIntoPieces_withEmptySeparator()
    {
        // when
        $pieces = Pattern::of('()(;) ?')->splitEnd('One; Two; Three; Four', 2);
        // then
        $this->assertSame(['One; Two', '', ';', 'Three', '', ';', 'Four'], $pieces);
    }

    /**
     * @test
     */
    public function shouldSplitEndIntoPieces_withUnmatchedSeparator()
    {
        // when
        $pieces = Pattern::of('(,)?(;) ?')->splitEnd('One,; Two; Three,; Four', 2);
        // then
        $this->assertSame(['One,; Two', null, ';', 'Three', ',', ';', 'Four'], $pieces);
    }

    /**
     * @test
     */
    public function shouldReturnSubject_onUnmatchedSubject()
    {
        // when
        $pieces = Pattern::of(' ')->splitStart('Bar', 0);
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
        Pattern::of('+')->splitEnd('Foo', 1);
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
        Pattern::of('invalid)')->splitEnd('Foo', -1);
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
        Pattern::of('invalid)')->splitEnd('Foo', -2);
    }

    /**
     * @test
     */
    public function shouldLimitMoreSeparatorsLessSplits()
    {
        // when
        $pieces = Pattern::of('(;)(;)')->splitEnd('One;;Two;;Three;;', 5);
        // then
        $this->assertSame(['One', ';', ';', 'Two', ';', ';', 'Three', ';', ';', ''], $pieces);
    }
}
