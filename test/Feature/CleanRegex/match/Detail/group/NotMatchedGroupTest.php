<?php
namespace Test\Feature\CleanRegex\match\Detail\group;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Internal\Match\Details\Group\NotMatchedGroup;
use TRegx\CleanRegex\Pattern;

class NotMatchedGroupTest extends TestCase
{
    /**
     * @test
     */
    public function shouldThrow_text()
    {
        // given
        $group = $this->group();
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call text() for group 'group', but the group was not matched");
        // when
        $group->text();
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
        $this->assertSame('13', $result);
    }

    /**
     * @test
     */
    public function shouldNotBeEqual()
    {
        // given
        $group = $this->group();
        // when, then
        $this->assertFalse($group->equals('any'));
    }

    /**
     * @test
     */
    public function shouldThrow_length()
    {
        // given
        $group = $this->group();
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call length() for group 'group', but the group was not matched");
        // when
        $group->length();
    }

    /**
     * @test
     */
    public function shouldThrow_byteLength()
    {
        // given
        $group = $this->group();
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call byteLength() for group 'group', but the group was not matched");
        // when
        $group->byteLength();
    }

    /**
     * @test
     */
    public function shouldThrow_tail()
    {
        // given
        $group = $this->group();
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call tail() for group 'group', but the group was not matched");
        // when
        $group->tail();
    }

    /**
     * @test
     */
    public function shouldThrow_byteTail()
    {
        // given
        $group = $this->group();
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call byteTail() for group 'group', but the group was not matched");
        // when
        $group->byteTail();
    }

    /**
     * @test
     */
    public function shouldThrow_offset()
    {
        // given
        $group = $this->group();
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call offset() for group 'group', but the group was not matched");
        // when
        $group->offset();
    }

    /**
     * @test
     */
    public function shouldThrow_byteOffset()
    {
        // given
        $group = $this->group();
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call byteOffset() for group 'group', but the group was not matched");
        // when
        $group->byteOffset();
    }

    /**
     * @test
     */
    public function shouldThrow_isInt()
    {
        // given
        $group = $this->group();
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call isInt() for group 'group', but the group was not matched");
        // when
        $group->isInt();
    }

    /**
     * @test
     */
    public function shouldThrow_toInt()
    {
        // given
        $group = $this->group();
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call toInt() for group 'group', but the group was not matched");
        // when
        $group->toInt();
    }

    private function group(): NotMatchedGroup
    {
        $detail = Pattern::of('(Foo)(?<group>Bar)?')->match('Foo')->first();
        /**
         * @var NotMatchedGroup $group
         */
        $group = $detail->group('group');
        return $group;
    }
}
