<?php
namespace Test\Feature\CleanRegex\PatternList\replace\callback;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Match\Detail;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;

/**
 * @covers \TRegx\CleanRegex\PatternList::replace
 * @covers \TRegx\CleanRegex\Replace\ChainedReplace
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
        $patterns = [
            "at's ai",
            "th__r you're (?<group>bre)(?<unmatched>lol)?",
            "nk __ath",
            "thi__ing",
            '(\s+|\?)',
        ];
        $pattern = Pattern::list(\array_slice($patterns, 0, $times));
        // when
        $replaced = $pattern
            ->replace("Do you think that's air you're breathing now?")
            ->callback(Functions::constant('__'));
        // then
        $this->assertSame($expected, $replaced);
    }

    public function times(): array
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
        $pattern = Pattern::list(['[a-z]', '[1-9]']);
        $replace = $pattern->replace('a 1 b 2 c 3');
        $matches = [];
        $subjects = [];
        // when
        $result = $replace->callback(function (Detail $detail) use (&$matches, &$subjects) {
            $matches[] = $detail->text();
            $subjects[] = $detail->subject();
            return '_';
        });
        // then
        $first = 'a 1 b 2 c 3';
        $second = '_ 1 _ 2 _ 3';
        $expectedResult = '_ _ _ _ _ _';

        $this->assertSame(['a', 'b', 'c', '1', '2', '3'], $matches);
        $this->assertSame([$first, $first, $first, $second, $second, $second], $subjects);
        $this->assertSame($expectedResult, $result);
    }

    /**
     * @test
     */
    public function shouldThrowForMalformedPattern()
    {
        // given
        $patternList = Pattern::list(['\\']);
        $replace = $patternList->replace('subject');
        // when
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Pattern may not end with a trailing backslash');
        // when
        $replace->callback(Functions::fail());
    }
}
