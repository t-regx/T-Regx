<?php
namespace Test\Feature\CleanRegex\match\count;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;
use function pattern;

/**
 * @covers \TRegx\CleanRegex\Match\Matcher
 */
class MatcherTest extends TestCase
{
    use AssertsDetail;

    /**
     * @test
     */
    public function shouldCount()
    {
        // when
        $count = pattern('\w+')->match('One, One, One, Two, One, Three, Two, One')->count();
        // then
        $this->assertSame(8, $count);
    }

    /**
     * @test
     */
    public function shouldCount_onUnmatchedSubject()
    {
        // when
        $count = pattern('Foo')->match('Bar')->count();
        // then
        $this->assertSame(0, $count);
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
        $matcher->count();
    }
}
