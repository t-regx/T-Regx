<?php
namespace Test\Feature\CleanRegex\Match\stream\distinct;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsDetail;
use Test\Utils\Functions;
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
    public function shouldRemoveDuplicates()
    {
        // given
        $stream = Pattern::of('Foo')
            ->search('Foo')
            ->stream()
            ->flatMapAssoc(Functions::constant(['12', '12', 12, '12' => 13]));
        // when
        $distinct = $stream->distinct()->all();
        // then
        $this->assertSame(['12', 2 => 12, '12' => 13], $distinct);
    }
}
