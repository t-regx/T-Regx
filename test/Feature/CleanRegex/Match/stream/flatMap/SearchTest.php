<?php
namespace Test\Feature\CleanRegex\Match\stream\flatMap;

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
    public function shouldFlatMap()
    {
        // when
        $stream = Pattern::of('.')->search('Apple')
            ->stream()
            ->flatMapAssoc(function ($argument) {
                return [$argument => $argument];
            });
        // when
        $entries = $stream->all();
        // then
        $expected = [
            'A' => 'A',
            'p' => 'p',
            'l' => 'l',
            'e' => 'e',
        ];
        $this->assertSame($expected, $entries);
    }
}
