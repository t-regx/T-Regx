<?php
namespace Test\Feature\CleanRegex\Match\nth;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;
use TRegx\CleanRegex\Exception\NoSuchNthElementException;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;

/**
 * @covers \TRegx\CleanRegex\Match\MatchPattern
 */
class MatchPatternTest extends TestCase
{
    use AssertsDetail;

    /**
     * @test
     */
    public function shouldGet_nth()
    {
        // given
        $match = pattern('\d+(cm|mm)')->match('12cm 14mm 13cm 19cm');
        // when
        $detail = $match->nth(3);
        // then
        $this->assertDetailText('19cm', $detail);
        $this->assertDetailIndex(3, $detail);
        $this->assertDetailOffset(15, $detail);
    }

    /**
     * @test
     */
    public function shouldThrow_nth_onUnmatchedSubject()
    {
        // given
        $match = pattern('Not matching')->match('Lorem Ipsum');
        // then
        $this->expectException(NoSuchNthElementException::class);
        $this->expectExceptionMessage("Expected to get the 6-nth match, but subject was not matched at all");
        // when
        $match->nth(6);
    }

    /**
     * @test
     */
    public function shouldThrow_nth_forMissingMatch()
    {
        // given
        $match = pattern('\d+(cm|mm)')->match('12cm, 14mm');
        // then
        $this->expectException(NoSuchNthElementException::class);
        $this->expectExceptionMessage("Expected to get the 6-nth match, but only 2 occurrences were matched");
        // when
        $match->nth(6);
    }

    /**
     * @test
     */
    public function shouldThrow_nth_forNegativeArgument()
    {
        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Negative nth: -6");
        // when
        pattern('Bar')->match('Bar')->nth(-6);
    }

    /**
     * @test
     */
    public function shouldThrowForMalformedPattern()
    {
        // given
        $match = Pattern::of('+')->match('Foo');
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        $match->nth(0);
    }
}
