<?php
namespace Test\Feature\CleanRegex\match\Detail\matched;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Pattern;

class DetailTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGroupBeMatched()
    {
        // given
        $detail = Pattern::of('(Foo)(Bar)?')->match('Foo')->first();
        // when
        $matched = $detail->matched(1);
        // then
        $this->assertTrue($matched);
    }

    /**
     * @test
     */
    public function shouldGroupNotBeMatched()
    {
        // given
        $detail = Pattern::of('(Foo)(Bar)?')->match('Foo')->first();
        // when
        $matched = $detail->matched(2);
        // then
        $this->assertFalse($matched);
    }

    /**
     * @test
     */
    public function shouldEmptyGroupBeMatched()
    {
        // given
        $detail = Pattern::of('(Foo)()')->match('Foo')->first();
        // when
        $matched = $detail->matched(2);
        // then
        $this->assertTrue($matched);
    }

    /**
     * @test
     */
    public function shouldThrow_forNonexistentGroup()
    {
        // given
        $detail = Pattern::of('Foo')->match('Foo')->first();
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'bar'");
        // when
        $detail->matched('bar');
    }

    /**
     * @test
     */
    public function shouldThrow_forInvalidGroup()
    {
        // given
        $detail = Pattern::of('Foo')->match('Foo')->first();
        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string, not starting with a digit, but '2group' given");
        // when
        $detail->matched('2group');
    }
}
