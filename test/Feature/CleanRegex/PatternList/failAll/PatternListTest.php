<?php
namespace Test\Feature\CleanRegex\PatternList\failAll;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\PatternMalformedPatternException;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;

/**
 * @covers \TRegx\CleanRegex\PatternList::failAll
 */
class PatternListTest extends TestCase
{
    /**
     * @test
     */
    public function testAllPassingPatterns()
    {
        // given
        $pattern = Pattern::list(['^P', 'R', 'E$']);
        // when, then
        $this->assertFalse($pattern->failAll('PRE'));
    }

    /**
     * @test
     */
    public function testOneFailingPattern()
    {
        // given
        $pattern = Pattern::list(['^P$', 'R', 'E', 'x']);
        // when, then
        $this->assertFalse($pattern->failAll('PRE'));
    }

    /**
     * @test
     */
    public function testAllFailingPatterns()
    {
        // given
        $pattern = Pattern::list(['1', '2', '3', '4']);
        // when, then
        $this->assertTrue($pattern->failAll('PRE'));
    }

    /**
     * @test
     */
    public function shouldFailAll()
    {
        // given
        $pattern = Pattern::list(['Foo', Pattern::of('Foo')]);
        // when, then
        $this->assertTrue($pattern->failAll('failing'));
    }

    /**
     * @test
     * @dataProvider patternLists
     */
    public function shouldNotFailAll(array $list)
    {
        // given
        $pattern = Pattern::list($list);
        // when, then
        $this->assertFalse($pattern->failAll('matching'));
    }

    public function patternLists(): array
    {
        return [
            [[Pattern::literal('matching'), 'matching']],
            [['failing', Pattern::literal('matching')]],
            [['matching', Pattern::literal('failing')]],
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
        $patternList->failAll('subject');
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
        $list->failAll('subject');
    }
}
