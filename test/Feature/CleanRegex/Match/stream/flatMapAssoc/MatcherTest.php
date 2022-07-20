<?php
namespace Test\Feature\CleanRegex\Match\stream\flatMapAssoc;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Match\Detail;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\Matcher
 */
class MatcherTest extends TestCase
{
    /**
     * @test
     */
    public function shouldFlatMapAssoc()
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

    /**
     * @test
     */
    public function shouldReturn_firstKey_forEmptyFirst()
    {
        // when
        $first = Pattern::of('(?<=")\w*(?=")')->match('"", "", "Apple"')
            ->stream()
            ->flatMapAssoc(Functions::lettersAsKeys())
            ->keys()
            ->first();
        // then
        $this->assertSame('A', $first);
    }
}
