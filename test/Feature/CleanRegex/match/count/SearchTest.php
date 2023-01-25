<?php
namespace Test\Feature\CleanRegex\match\count;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;
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
    public function shouldCountMatches()
    {
        // when
        $count = Pattern::of('\w+')->search('One, One, One, Two, One, Three, Two, One')->count();
        // then
        $this->assertSame(8, $count);
    }

    /**
     * @test
     */
    public function shouldCountMatches_onUnmatchedSubject()
    {
        // when
        $count = Pattern::of('Foo')->search('Bar')->count();
        // then
        $this->assertSame(0, $count);
    }

    /**
     * @test
     */
    public function shouldThrowForMalformedPattern()
    {
        // given
        $search = Pattern::of('+')->search('Bar');
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        $search->count();
    }
}
