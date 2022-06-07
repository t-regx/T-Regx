<?php
namespace Test\Feature\CleanRegex\Match\nth;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;
use TRegx\CleanRegex\Exception\NoSuchNthElementException;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;

/**
 * @covers \TRegx\CleanRegex\Match\Search
 */
class SearchTest extends TestCase
{
    use AssertsDetail;

    /**
     * @test
     */
    public function shouldGet_nth()
    {
        // given
        $search = pattern('\d+(cm|mm)')->search('12cm 14mm 13cm 19cm');
        // when
        $nth = $search->nth(3);
        // then
        $this->assertSame('19cm', $nth);
    }

    /**
     * @test
     */
    public function shouldThrow_nth_onUnmatchedSubject()
    {
        // given
        $search = pattern('Not matching')->search('Lorem Ipsum');
        // then
        $this->expectException(NoSuchNthElementException::class);
        $this->expectExceptionMessage("Expected to get the 6-nth match, but subject was not matched at all");
        // when
        $search->nth(6);
    }

    /**
     * @test
     */
    public function shouldThrow_nth_forMissingMatch()
    {
        // given
        $search = pattern('\d+(cm|mm)')->search('12cm, 14mm');
        // then
        $this->expectException(NoSuchNthElementException::class);
        $this->expectExceptionMessage("Expected to get the 6-nth match, but only 2 occurrences were matched");
        // when
        $search->nth(6);
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
        pattern('Bar')->search('Bar')->nth(-6);
    }

    /**
     * @test
     */
    public function shouldThrowForMalformedPattern()
    {
        // given
        $search = Pattern::of('+')->search('Foo');
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        $search->nth(0);
    }
}
