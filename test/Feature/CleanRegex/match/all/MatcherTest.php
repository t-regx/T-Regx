<?php
namespace Test\Feature\CleanRegex\match\all;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;
use Test\Utils\Structure\AssertsStructure;
use Test\Utils\Structure\Expect;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;

/**
 * @covers \TRegx\CleanRegex\Match\Matcher
 */
class MatcherTest extends TestCase
{
    use AssertsDetail, AssertsStructure;

    /**
     * @test
     */
    public function shouldReturnAll()
    {
        // when
        $details = Pattern::of('Foo (B(ar))')->match('Foo Bar, Foo Bar, Foo Bar')->all();
        // then
        $this->assertStructure($details, [
            Expect::text('Foo Bar'),
            Expect::text('Foo Bar'),
            Expect::text('Foo Bar'),
        ]);
        $this->assertStructure($details, [
            Expect::offset(0),
            Expect::offset(9),
            Expect::offset(18),
        ]);
        $this->assertDetailsIndexed(...$details);
    }

    /**
     * @test
     */
    public function shouldReturnEmpty_onUnmatchedSubject()
    {
        // when
        $details = Pattern::of('Foo')->match('Bar')->all();
        // then
        $this->assertEmpty($details);
    }

    /**
     * @test
     */
    public function shouldThrowForMalformedPattern()
    {
        // given
        $matcher = Pattern::of('+')->match('Bar');
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        $matcher->all();
    }
}
