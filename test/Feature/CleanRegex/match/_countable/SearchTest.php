<?php
namespace Test\Feature\CleanRegex\match\_countable;

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
    public function shouldBeCountable()
    {
        // given
        $search = Pattern::of('\w+')->search('One, One, One, Two, One, Three, Two, One');
        // when
        $count = \count($search);
        // then
        $this->assertSame(8, $count);
    }
}
