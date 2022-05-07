<?php
namespace Test\Feature\TRegx\CleanRegex\Match\Details\group;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Match\Details\Group\NotMatchedGroup;
use TRegx\CleanRegex\Match\MatchPattern;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\Details\Group\NotMatchedGroup
 */
class NotMatchedGroupTest extends TestCase
{
    /**
     * @test
     */
    public function shouldNotBeMatched()
    {
        // given
        $group = $this->groupOf();
        // when, then
        $this->assertFalse($group->matched());
    }

    /**
     * @test
     */
    public function shouldNotBeEqual()
    {
        // given
        $group = $this->groupOf();
        // when, then
        $this->assertFalse($group->equals('any'));
    }

    /**
     * @test
     */
    public function shouldGetName()
    {
        // given
        $group = $this->groupOf();
        // when, then
        $this->assertSame('first', $group->name());
    }

    /**
     * @test
     */
    public function shouldGetIndex()
    {
        // given
        $group = $this->groupOf();
        // when, then
        $this->assertSame(1, $group->index());
    }

    /**
     * @test
     */
    public function shouldCallOrReturn()
    {
        // given
        $group = $this->groupOf();
        // when
        $result = $group->or(13);
        // when, then
        $this->assertSame('13', $result);
    }

    /**
     * @test
     */
    public function shouldThrow_text()
    {
        // given
        $group = $this->groupOf();
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call text() for group 'first', but the group was not matched");
        // when
        $group->text();
    }

    /**
     * @test
     */
    public function shouldThrow_length()
    {
        // given
        $group = $this->groupOf();
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call length() for group 'first', but the group was not matched");
        // when
        $group->length();
    }

    /**
     * @test
     */
    public function shouldThrow_byteTextLength()
    {
        // given
        $group = $this->groupOf();
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call textByteLength() for group 'first', but the group was not matched");
        // when
        $group->textByteLength();
    }

    /**
     * @test
     */
    public function shouldThrow_tail()
    {
        // given
        $group = $this->groupOf();
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call tail() for group 'first', but the group was not matched");
        // when
        $group->tail();
    }

    /**
     * @test
     */
    public function shouldThrow_byteTail()
    {
        // given
        $group = $this->groupOf();
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call byteTail() for group 'first', but the group was not matched");
        // when
        $group->byteTail();
    }

    /**
     * @test
     */
    public function shouldThrow_offset()
    {
        // given
        $group = $this->groupOf();
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call offset() for group 'first', but the group was not matched");
        // when
        $group->offset();
    }

    /**
     * @test
     */
    public function shouldThrow_byteOffset()
    {
        // given
        $group = $this->groupOf();
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call byteOffset() for group 'first', but the group was not matched");
        // when
        $group->byteOffset();
    }

    /**
     * @test
     */
    public function shouldThrow_substitute()
    {
        // given
        $group = $this->groupOf();
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call substitute() for group 'first', but the group was not matched");
        // when
        $group->substitute('');
    }

    private function groupOf(): NotMatchedGroup
    {
        return $this->groupOfFirst(Pattern::of('Foo(?<first>first)?')->match('Foo'));
    }

    private function groupOfFirst(MatchPattern $matchPattern): NotMatchedGroup
    {
        $matchPattern->first(Functions::out($detail));
        return $detail->group('first');
    }
}
