<?php
namespace Test\Feature\CleanRegex\PatternList\testAny;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\PatternMalformedPatternException;
use TRegx\CleanRegex\Pattern;
use TRegx\CleanRegex\PcrePattern;
use TRegx\Exception\MalformedPatternException;

/**
 * @covers \TRegx\CleanRegex\PatternList::testAny
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
        $this->assertTrue($pattern->testAny('http'));
    }

    /**
     * @test
     */
    public function shouldNotMatch()
    {
        // given
        $pattern = Pattern::list($this->patterns());
        // when, then
        $this->assertFalse($pattern->testAny('Foo'));
    }

    private function patterns(): array
    {
        return [
            PcrePattern::of('/https?/i'),
            PcrePattern::of('/fail/'),
            PcrePattern::of('/failed/i')
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
        $patternList->testAny('subject');
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
        $list->testAny('subject');
    }
}
