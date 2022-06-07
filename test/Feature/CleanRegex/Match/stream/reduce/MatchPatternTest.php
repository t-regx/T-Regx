<?php
namespace Test\Feature\CleanRegex\Match\stream\reduce;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\MatchPattern
 */
class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReduce_passDetailSecondAsArgumentDetail()
    {
        // given
        $match = Pattern::of('Foo')->match('Foo');
        $detailText = function ($acc, Detail $detail) {
            return $detail->text();
        };
        // when
        $result = $match
            ->stream()
            ->reduce($detailText, 'Accumulator');
        // then
        $this->assertSame('Foo', $result);
    }
}
