<?php
namespace Test\Feature\CleanRegex\PatternList\failAny;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\PatternList::failAny
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
        $this->assertFalse($pattern->failAny('PRE'));
    }

    /**
     * @test
     */
    public function testOneFailingPattern()
    {
        // given
        $pattern = Pattern::list(['^P$', 'R', 'E', 'x']);
        // when, then
        $this->assertTrue($pattern->failAny('PRE'));
    }

    /**
     * @test
     */
    public function testAllFailingPatterns()
    {
        // given
        $pattern = Pattern::list(['/1/', '/2/', '/3/', '/4/']);
        // when, then
        $this->assertTrue($pattern->failAny('PRE'));
    }

    /**
     * @test
     */
    public function shouldNotFailAny()
    {
        // given
        $pattern = Pattern::list(['matching', Pattern::of('matching')]);
        // when, then
        $this->assertFalse($pattern->failAll('matching'));
    }

    /**
     * @test
     * @dataProvider patternLists
     */
    public function shouldFailAny(array $list)
    {
        // given
        $pattern = Pattern::list($list);
        // when, then
        $this->assertTrue($pattern->failAny('matching'));
    }

    public function patternLists(): array
    {
        return [
            [[Pattern::literal('failing'), 'failing']],
            [['failing', Pattern::literal('matching')]],
            [['matching', Pattern::literal('failing')]],
        ];
    }
}
