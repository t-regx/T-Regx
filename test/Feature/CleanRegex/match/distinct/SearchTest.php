<?php
namespace Test\Feature\CleanRegex\match\distinct;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;
use TRegx\CleanRegex\Pattern;

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
        $details = Pattern::of('\w+')->search('One, One, One, Two, One, Three, Two, One')->distinct();
        // then
        $this->assertSame(['One', 'Two', 'Three'], $details);
    }

    /**
     * @test
     */
    public function shouldReturnAll()
    {
        // when
        $details = Pattern::of('\S{5,}')->search("I'm disinclined to acquiesce to your request")->distinct();
        // then
        $this->assertSame(['disinclined', 'acquiesce', 'request'], $details);
    }

    /**
     * @test
     */
    public function shouldReturnEmpty_onUnmatchedSubject()
    {
        // when
        $details = Pattern::of('Foo')->search('Bar')->distinct();
        // then
        $this->assertEmpty($details);
    }
}
