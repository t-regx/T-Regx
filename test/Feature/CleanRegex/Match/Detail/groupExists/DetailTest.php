<?php
namespace Test\Feature\CleanRegex\Match\Detail\groupExists;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;

class DetailTest extends TestCase
{
    /**
     * @test
     */
    public function shouldHaveGroup()
    {
        // given
        $detail = Pattern::of('(?<foo>value)')->match('value')->first();
        // when
        $existent = $detail->groupExists(1);
        // then
        $this->assertTrue($existent);
    }

    /**
     * @test
     */
    public function shouldNotHaveGroup()
    {
        // given
        $detail = Pattern::of('(?<foo>value)')->match('value')->first();
        // when
        $nonExistent = $detail->groupExists(2);
        // then
        $this->assertFalse($nonExistent);
    }

    /**
     * @test
     */
    public function shouldHaveNamedGroup()
    {
        // given
        $detail = Pattern::of('(?<foo>foo)')->match('foo')->first();
        // when
        $existent = $detail->groupExists('foo');
        // then
        $this->assertTrue($existent);
    }

    /**
     * @test
     */
    public function shouldNotHaveNamedGroup()
    {
        // given
        $detail = Pattern::of('(?<foo>bar)')->match('bar')->first();
        // when
        $nonExistent = $detail->groupExists('bar');
        // then
        $this->assertFalse($nonExistent);
    }

    /**
     * @test
     */
    public function shouldThrow_forInvalidNamedGroup()
    {
        // given
        $detail = Pattern::of('Foo')->match('Foo')->first();
        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string, not starting with a digit, but '2group' given");
        // when
        $detail->matched('2group');
    }

    /**
     * @test
     */
    public function shouldThrow_forInvalidIndexedGroup()
    {
        // given
        $detail = Pattern::of('Foo')->match('Foo')->first();
        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Group index must be a non-negative integer, but -2 given');
        // when
        $detail->matched(-2);
    }
}
