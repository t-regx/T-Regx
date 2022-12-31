<?php
namespace Test\Feature\CleanRegex\match\_countable;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;
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
    public function shouldBeCountable()
    {
        // given
        $matcher = pattern('\w+')->match('One, One, One, Two, One, Three, Two, One');
        // when
        $count = \count($matcher);
        // then
        $this->assertSame(8, $count);
    }
}
