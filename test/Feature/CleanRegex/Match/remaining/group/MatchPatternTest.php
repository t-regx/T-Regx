<?php
namespace Test\Feature\TRegx\CleanRegex\Match\remaining\group;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsSameMatches;
use Test\Utils\Functions;

class MatchPatternTest extends TestCase
{
    use AssertsSameMatches;

    /**
     * @test
     */
    public function shouldBeIterable()
    {
        // given
        $iterable = pattern('\d+([cm]?m)')
            ->match('14cm 12mm 18m')
            ->remaining(Functions::notEquals('12mm'))
            ->group(1);

        // when
        $result = iterator_to_array($iterable);

        // then
        $this->assertSameMatches(['cm', 'm'], $result);
    }
}
