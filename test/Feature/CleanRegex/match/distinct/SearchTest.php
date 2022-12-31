<?php
namespace Test\Feature\CleanRegex\match\distinct;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;
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
    public function shouldReturnAllDistinct()
    {
        // when
        $details = pattern('\w+')->search('One, One, One, Two, One, Three, Two, One')->distinct();
        // then
        $this->assertSame(['One', 'Two', 'Three'], $details);
    }

    /**
     * @test
     */
    public function shouldReturnAll()
    {
        // when
        $details = pattern('\S{5,}')->search("I'm disinclined to acquiesce to your request")->distinct();
        // then
        $this->assertSame(['disinclined', 'acquiesce', 'request'], $details);
    }

    /**
     * @test
     */
    public function shouldReturnEmpty_onUnmatchedSubject()
    {
        // when
        $details = pattern('Foo')->search('Bar')->distinct();
        // then
        $this->assertEmpty($details);
    }
}
