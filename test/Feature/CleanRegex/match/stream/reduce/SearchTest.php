<?php
namespace Test\Feature\CleanRegex\match\stream\reduce;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\Search
 */
class SearchTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReduce_passDetailSecondAsArgumentString()
    {
        // given
        $search = Pattern::of('Foo')->search('Foo');
        $detailText = function ($acc, $argument) {
            $this->assertIsString($argument);
        };
        // when
        $search->stream()->reduce($detailText, 'Accumulator');
    }
}
