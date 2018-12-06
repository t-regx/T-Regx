<?php
namespace Test\Integration\TRegx\CleanRegex\CompositePattern\chainedReplace\callback;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\CompositePattern;
use TRegx\CleanRegex\Internal\CompositePatternMapper;
use TRegx\CleanRegex\Match\Details\ReplaceMatch;
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
            "th__r you're (?<group>bre)(?<unmatched>lol)?",
            "nk __ath",
            "thi__ing",
            '(\s+|\?)',
        ];
        $sliced = array_slice($patterns, 0, $times);
        $pattern = new CompositePattern((new CompositePatternMapper($sliced))->createPatterns());

        // when
        $replaced = $pattern
            ->chainedReplace("Do you think that's air you're breathing now?")
            ->callback(function (ReplaceMatch $match) {
                return '__';
            });

        // then
        $this->assertEquals($expected, $replaced);
    }

    public function times()
    {
        return [
            [0, "Do you think that's air you're breathing now?"],
            [1, "Do you think th__r you're breathing now?"],
            [2, 'Do you think __athing now?'],
            [3, 'Do you thi__ing now?'],
            [4, 'Do you __ now?'],
            [5, 'Do__you______now__'],
            [6, 'Do__you______now__'],
            [7, 'Do__you______now__'],
        ];
    }

    /**
     * @test
     */
    public function shouldInvokeCallbackForOnePattern()
    {
        // given
        $pattern = new CompositePattern((new CompositePatternMapper(['[a-z]', '[1-9]']))->createPatterns());
        $chainedReplace = $pattern->chainedReplace('a 1 b 2 c 3');
        $matches = [];
        $subjects = [];
        $modified = [];

        // when
        $result = $chainedReplace->callback(function (ReplaceMatch $match) use (&$matches, &$subjects, &$modified) {
            $matches[] = $match->text();
            $subjects[] = $match->subject();
            $modified[] = $match->modifiedSubject();
            return '_';
        });

        // then
        $first = 'a 1 b 2 c 3';
        $second = '_ 1 _ 2 _ 3';
        $expectedModified = [
            'a 1 b 2 c 3',
            '_ 1 b 2 c 3',
            '_ 1 _ 2 c 3',
            '_ 1 _ 2 _ 3',
            '_ _ _ 2 _ 3',
            '_ _ _ _ _ 3',
        ];
        $expectedResult = '_ _ _ _ _ _';

        $this->assertEquals(['a', 'b', 'c', '1', '2', '3'], $matches);
        $this->assertEquals([$first, $first, $first, $second, $second, $second], $subjects);
        $this->assertEquals($expectedModified, $modified);
        $this->assertEquals($expectedResult, $result);
    }
}
