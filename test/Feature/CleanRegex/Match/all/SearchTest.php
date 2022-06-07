<?php
namespace Test\Feature\CleanRegex\Match\all;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;
use function pattern;

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
        $details = pattern('\S{5,}')->search("I'm disinclined to acquiesce to your request")->all();
        // then
        $this->assertSame(['disinclined', 'acquiesce', 'request'], $details);
    }

    /**
     * @test
     */
    public function shouldReturnEmpty_onUnmatchedSubject()
    {
        // when
        $details = pattern('Means')->search('No')->all();
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
