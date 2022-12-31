<?php
namespace Test\Feature\CleanRegex\match\distinct;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;
use Test\Utils\Structure\AssertsStructure;
use Test\Utils\Structure\Expect;
use function pattern;

/**
 * @covers \TRegx\CleanRegex\Match\Matcher
 */
class MatcherTest extends TestCase
{
    use AssertsDetail, AssertsStructure;

    /**
     * @test
     */
    public function shouldReturnAllDistinct()
    {
        // when
        $details = pattern('\w+')->match('One, One, One, Two, One, Three, Two, One')->distinct();
        // then
        $this->assertStructure($details, [
            Expect::text('One'),
            Expect::text('Two'),
            Expect::text('Three'),
        ]);
        $this->assertStructure($details, [
            Expect::index(0),
            Expect::index(3),
            Expect::index(5),
        ]);
    }

    /**
     * @test
     */
    public function shouldReturnAll()
    {
        // when
        $details = pattern('\S{5,}')->match("I'm disinclined to acquiesce to your request")->distinct();
        // then
        $this->assertStructure($details, [
            Expect::text('disinclined'),
            Expect::text('acquiesce'),
            Expect::text('request'),
        ]);
        $this->assertDetailsIndexed(...$details);
    }

    /**
     * @test
     */
    public function shouldReturnEmpty_onUnmatchedSubject()
    {
        // when
        $details = pattern('Foo')->match('Bar')->distinct();
        // then
        $this->assertEmpty($details);
    }
}
