<?php
namespace Test\Integration\TRegx\CleanRegex\CompositePattern\chainedRemove;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\CompositePattern;
use TRegx\CleanRegex\Internal\CompositePatternMapper;
use function array_slice;

class CompositePatternTest extends TestCase
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
        $patterns = [
            "at's ai",
            "thr you're bre",
            "nk ath",
            "thiing",
            '(\s+|\?)',
            "[ou]"
        ];
        $slicedPatterns = array_slice($patterns, 0, $times);
        $pattern = new CompositePattern((new CompositePatternMapper($slicedPatterns))->createPatterns());

        // when
        $replaced = $pattern->chainedRemove("Do you think that's air you're breathing now?");

        // then
        $this->assertEquals($expected, $replaced);
    }

    public function times()
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
