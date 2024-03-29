<?php
namespace Test\Feature\CleanRegex\PatternList\testAll;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\PatternMalformedPatternException;
use TRegx\CleanRegex\Pattern;
use TRegx\CleanRegex\PcrePattern;
use TRegx\Exception\MalformedPatternException;

/**
 * @covers \TRegx\CleanRegex\PatternList::testAll
 */
class PatternListTest extends TestCase
{
    /**
     * @test
     */
    public function shouldMatch()
    {
        // given
        $pattern = Pattern::list($this->patterns());
        // when, then
        $this->assertTrue($pattern->testAll('Frodo'));
    }

    /**
     * @test
     */
    public function shouldNotMatch()
    {
        // given
        $pattern = Pattern::list($this->patterns());
        // when, then
        $this->assertFalse($pattern->testAll('Frodo2'));
    }

    private function patterns(): array
    {
        return [
            PcrePattern::of('/^fro/i'),
            PcrePattern::of('/rod/'),
            PcrePattern::of('/odo$/')
        ];
    }

    /**
     * @test
     */
    public function shouldThrowForMalformedPattern()
    {
        // given
        $patternList = Pattern::list(['\\']);
        // when
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Pattern may not end with a trailing backslash');
        // when
        $patternList->testAll('subject');
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
        $list->testAll('subject');
    }
}
