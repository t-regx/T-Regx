<?php
namespace Test\Feature\CleanRegex\Match\count;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;
use function pattern;

/**
 * @covers \TRegx\CleanRegex\Match\MatchPattern
 */
class MatchPatternTest extends TestCase
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
        $match = Pattern::of('+')->match('Bar');
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        $match->count();
    }
}
