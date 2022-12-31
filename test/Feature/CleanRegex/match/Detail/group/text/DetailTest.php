<?php
namespace Test\Feature\CleanRegex\match\Detail\group\text;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Pattern;

class DetailTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetText()
    {
        // given
        $detail = Pattern::of('(12€)(cm)', 'i')->match('€€ 12€cm')->first();
        // then
        $value = $detail->group(2)->text();
        // then
        $this->assertSame('cm', "$value");
    }

    /**
     * @test
     */
    public function shouldCast_toString()
    {
        // given
        $detail = Pattern::of('(12€)(cm)', 'i')->match('€€ 12€cm')->first();
        // then
        $value = (string)$detail->group(2);
        // then
        $this->assertSame('cm', "$value");
    }

    /**
     * @test
     */
    public function shouldThrow_forUnmatchedGroup()
    {
        // given
        $detail = pattern('(?<group>Foo)?')->match('Bar')->first();
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call text() for group 'group', but the group was not matched");
        // when
        $detail->group('group')->text();
    }
}
