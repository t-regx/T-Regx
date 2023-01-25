<?php
namespace Test\Feature\CleanRegex\match\all;

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
    public function shouldReturnAll()
    {
        // when
        $details = Pattern::of('\S{5,}')->search("I'm disinclined to acquiesce to your request")->all();
        // then
        $this->assertSame(['disinclined', 'acquiesce', 'request'], $details);
    }

    /**
     * @test
     */
    public function shouldReturnEmpty_onUnmatchedSubject()
    {
        // when
        $details = Pattern::of('Means')->search('No')->all();
        // then
        $this->assertEmpty($details);
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
        $search->all();
    }
}
