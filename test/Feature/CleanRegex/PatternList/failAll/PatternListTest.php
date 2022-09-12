<?php
namespace Test\Feature\CleanRegex\PatternList\failAll;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;

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
}
