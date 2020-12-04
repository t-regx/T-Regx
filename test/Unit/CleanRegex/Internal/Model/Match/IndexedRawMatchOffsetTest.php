<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Model\Match;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Model\Match\IndexedRawMatchOffset;

class IndexedRawMatchOffsetTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetIndex()
    {
        // given
        $match = new IndexedRawMatchOffset([], 14);

        // when
        $index = $match->getIndex();

        // then
        $this->assertEquals(14, $index);
    }
}
