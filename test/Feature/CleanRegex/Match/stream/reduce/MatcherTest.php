<?php
namespace Test\Feature\CleanRegex\Match\stream\reduce;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\Matcher
 */
class MatcherTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReduce_passDetailSecondAsArgumentDetail()
    {
        // given
        $matcher = Pattern::of('Foo')->match('Foo');
        $detailText = function ($acc, Detail $detail) {
            return $detail->text();
        };
        // when
        $result = $matcher
            ->stream()
            ->reduce($detailText, 'Accumulator');
        // then
        $this->assertSame('Foo', $result);
    }
}
