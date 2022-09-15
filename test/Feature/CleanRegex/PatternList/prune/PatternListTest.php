<?php
namespace Test\Feature\CleanRegex\PatternList\prune;

use PHPUnit\Framework\TestCase;
use Test\Utils\Backtrack\CausesBacktracking;
use TRegx\CleanRegex\Exception\PatternMalformedPatternException;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;
use TRegx\SafeRegex\Exception\CatastrophicBacktrackingException;

/**
 * @covers \TRegx\CleanRegex\PatternList::prune
 */
class PatternListTest extends TestCase
{
    use CausesBacktracking;

    /**
     * @test
     * @dataProvider times
     * @param int $times
     * @param string $expected
     */
    public function test(int $times, string $expected)
    {
        // given
        $list = Pattern::list(\array_slice([
            "at's ai",
            "thr you're bre",
            'nk ath',
            'thi{2}ng',
            '(\s+|\?)',
            '[ou]',
        ], 0, $times));
        // when
        $replaced = $list->prune("Do you think that's air you're breathing now?");
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

    /**
     * @test
     */
    public function shouldThrowForMalformedPatternTemplate()
    {
        // given
        $patternList = Pattern::list(['Foo\\']);
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Pattern may not end with a trailing backslash');
        // when
        $patternList->prune('subject');
    }

    /**
     * @test
     */
    public function shouldThrowForMalformedPattern()
    {
        // given
        $list = Pattern::list(['+']);
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when, then
        $list->prune('Fail');
    }

    /**
     * @test
     */
    public function shouldThrowForMalformedPatternMiddle()
    {
        // given
        $list = Pattern::list(['Foo', '+']);
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when, then
        $list->prune('Fail');
    }

    /**
     * @test
     */
    public function shouldPreferTemplateMalformedPattern()
    {
        // given
        $list = Pattern::list(['+', 'Foo\\']);
        // then
        $this->expectException(PatternMalformedPatternException::class);
        $this->expectExceptionMessage('Pattern may not end with a trailing backslash');
        // when, then
        $list->prune('subject');
    }

    /**
     * @test
     */
    public function shouldThrowForCatastrophicBacktracking()
    {
        // given
        $list = Pattern::list([
            $this->backtrackingPattern()
        ]);
        // then
        $this->expectException(CatastrophicBacktrackingException::class);
        // when, then
        $list->prune($this->backtrackingSubject(0));
    }
}
