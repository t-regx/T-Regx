<?php
namespace Test\Feature\CleanRegex\Match\Detail\group;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Match\Details\Group\MatchedGroup;
use TRegx\CleanRegex\Pattern;

class MatchedGroupTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGet_text()
    {
        // given
        $group = $this->group();
        // when
        $text = $group->text();
        // then
        $this->assertSame('group,€', $text);
    }

    /**
     * @test
     */
    public function shouldCallOrReturn()
    {
        // given
        $group = $this->group();
        // when
        $result = $group->or(13);
        // when, then
        $this->assertSame('group,€', $result);
    }

    /**
     * @test
     */
    public function shouldEqual()
    {
        // given
        $group = $this->group();
        // when, then
        $this->assertTrue($group->equals('group,€'));
        $this->assertFalse($group->equals('something else'));
    }

    /**
     * @test
     */
    public function shouldThrow_length()
    {
        // given
        $group = $this->group();
        // when
        $length = $group->length();
        $byteLength = $group->byteLength();
        // then
        $this->assertSame(7, $length);
        $this->assertSame(9, $byteLength);
    }

    /**
     * @test
     */
    public function shouldThrow_tail()
    {
        // given
        $group = $this->group();
        // when
        $tail = $group->tail();
        $byteTail = $group->byteTail();
        // then
        $this->assertSame(13, $tail);
        $this->assertSame(17, $byteTail);
    }

    /**
     * @test
     */
    public function shouldThrow_offset()
    {
        // given
        $group = $this->group();
        // when
        $offset = $group->offset();
        $byteOffset = $group->byteOffset();
        // then
        $this->assertSame(6, $offset);
        $this->assertSame(8, $byteOffset);
    }

    /**
     * @test
     * @dataProvider integers
     */
    public function shouldParseIntegerBase10Default(string $text, int $expected, array $arguments)
    {
        // given
        $matchedGroup = Pattern::of('(\w+)')->match($text)->first()->group(1);
        // when
        $integer = $matchedGroup->toInt(...$arguments);
        // then
        $this->assertSame($expected, $integer);
    }

    public function integers(): array
    {
        return [
            ['194', 194, []],
            ['194', 194, [10]],
            ['a4f', 2639, [16]],
        ];
    }

    private function group(): MatchedGroup
    {
        $detail = Pattern::of('(\d+):(?<group>group,€)')->match('€, 12:group,€')->first();
        /**
         * @var MatchedGroup $group
         */
        $group = $detail->group(2);
        return $group;
    }
}
