<?php
namespace Test\Feature\CleanRegex\Match\stream\flatMap;

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
    public function shouldFlatMap()
    {
        // when
        $stream = Pattern::of('.')->match('Apple')
            ->stream()
            ->flatMapAssoc(function (Detail $detail) {
                return [$detail->text() => $detail->offset()];
            });
        // when
        $entries = $stream->all();
        // then
        $expected = [
            'A' => 0,
            'p' => 2,
            'l' => 3,
            'e' => 4,
        ];
        $this->assertSame($expected, $entries);
    }
}
