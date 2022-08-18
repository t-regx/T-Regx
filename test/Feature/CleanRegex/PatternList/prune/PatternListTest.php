<?php
namespace Test\Feature\CleanRegex\PatternList\prune;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\PatternList::prune
 */
class PatternListTest extends TestCase
{
    /**
     * @test
     * @dataProvider times
     * @param int $times
     * @param string $expected
     */
    public function test(int $times, string $expected)
    {
        // given
        $composite = Pattern::compose(\array_slice([
            "at's ai",
            "thr you're bre",
            "nk ath",
            "thi{2}ng",
            '(\s+|\?)',
            "[ou]",
        ], 0, $times));
        // when
        $replaced = $composite->prune("Do you think that's air you're breathing now?");
        // then
        $this->assertSame($expected, $replaced);
    }

    public function times(): array
    {
        return [
            [0, "Do you think that's air you're breathing now?"],
            [1, "Do you think thr you're breathing now?"],
            [2, "Do you think athing now?"],
            [3, "Do you thiing now?"],
            [4, "Do you  now?"],
            [5, "Doyounow"],
            [6, "Dynw"],
        ];
    }
}
