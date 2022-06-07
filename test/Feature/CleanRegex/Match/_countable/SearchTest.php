<?php
namespace Test\Feature\CleanRegex\Match\_countable;

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
    public function shouldBeCountable()
    {
        // given
        $search = pattern('\w+')->search('One, One, One, Two, One, Three, Two, One');
        // when
        $count = \count($search);
        // then
        $this->assertSame(8, $count);
    }
}
