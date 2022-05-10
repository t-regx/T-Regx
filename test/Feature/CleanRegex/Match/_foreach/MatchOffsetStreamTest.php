<?php
namespace Test\Feature\TRegx\CleanRegex\Match\_foreach;

use PHPUnit\Framework\TestCase;
use function pattern;

/**
 * @coversNothing
 */
class MatchOffsetStreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldIterateMatchGroupOffset()
    {
        // given
        $group = pattern('\d+([cm]?m)')->match('14cm 127mm 18m')->group(1);
        $result = [];

        // when
        foreach ($group->offsets() as $offset) {
            $result[] = $offset;
        }

        // then
        $this->assertSame([2, 8, 13], $result);
    }
}
