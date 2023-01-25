<?php
namespace Test\Feature\CleanRegex\Split\split;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;

class PatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSplitIntoPieces()
    {
        // when
        $pieces = Pattern::of(', ?')->split('Father, Mother, Maiden, Crone, Warrior, Smith, Stranger');
        // then
        $this->assertSame(['Father', 'Mother', 'Maiden', 'Crone', 'Warrior', 'Smith', 'Stranger'], $pieces);
    }

    /**
     * @test
     */
    public function shouldSplitIntoPieces_withSeparator()
    {
        // when
        $pieces = Pattern::of('([,;]) ?')->split('Foo, Bar; Cat, Door');
        // then
        $this->assertSame(['Foo', ',', 'Bar', ';', 'Cat', ',', 'Door'], $pieces);
    }

    /**
     * @test
     */
    public function shouldSplitIntoPieces_withSeparators()
    {
        // when
        $pieces = Pattern::of('(;)(;) ?')->split('Foo;; Bar;; Cat;; Door');
        // then
        $this->assertSame(['Foo', ';', ';', 'Bar', ';', ';', 'Cat', ';', ';', 'Door'], $pieces);
    }

    /**
     * @test
     */
    public function shouldSplitIntoPieces_withSeparators_emptySeparator()
    {
        // when
        $pieces = Pattern::of('()(;) ?')->split('Foo; Bar; Cat; Door');
        // then
        $this->assertSame(['Foo', '', ';', 'Bar', '', ';', 'Cat', '', ';', 'Door'], $pieces);
    }

    /**
     * @test
     */
    public function shouldSplitIntoPieces_withOptionalGroup()
    {
        // when
        $pieces = Pattern::of('(,)?(;) ?')->split('One,; Two; Three,; Four');
        // then
        $this->assertSame(['One', ',', ';', 'Two', null, ';', 'Three', ',', ';', 'Four'], $pieces);
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
        Pattern::of('+')->split('Foo');
    }
}
