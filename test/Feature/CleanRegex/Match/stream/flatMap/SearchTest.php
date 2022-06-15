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
        $stream = Pattern::of('..?')->search('Apple')
            ->stream()
            ->flatMap(function ($argument) {
                return [$argument];
            });
        // when
        $entries = $stream->all();
        // then
        $this->assertSame(['Ap', 'pl', 'e'], $entries);
    }
}
