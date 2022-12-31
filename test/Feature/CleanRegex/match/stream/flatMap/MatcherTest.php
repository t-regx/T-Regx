<?php
namespace Test\Feature\CleanRegex\match\stream\flatMap;

use PHPUnit\Framework\TestCase;
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
    public function shouldFlatMap()
    {
        // when
        $stream = Pattern::of('..?')->match('Apple')
            ->stream()
            ->flatMap(function (Detail $detail) {
                return [$detail->offset()];
            });
        // when
        $entries = $stream->all();
        // then
        $this->assertSame([0, 2, 4], $entries);
    }
}
