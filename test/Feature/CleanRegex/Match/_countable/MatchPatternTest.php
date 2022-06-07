<?php
namespace Test\Feature\CleanRegex\Match\_countable;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;
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
    public function shouldBeCountable()
    {
        // given
        $match = pattern('\w+')->match('One, One, One, Two, One, Three, Two, One');
        // when
        $count = \count($match);
        // then
        $this->assertSame(8, $count);
    }
}
