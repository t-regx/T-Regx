<?php
namespace Test\Feature\CleanRegex\match\stream\toMap;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
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
            ->toMap(function ($argument) {
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

    /**
     * @test
     */
    public function shouldReturn_firstKey_forEmptyFirst()
    {
        // when
        $first = Pattern::of('(?<=")\w*(?=")')->search('"", "", "Apple"')
            ->stream()
            ->toMap(Functions::lettersAsKeys())
            ->keys()
            ->first();
        // then
        $this->assertSame('A', $first);
    }
}
