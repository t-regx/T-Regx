<?php
namespace Test\Feature\CleanRegex\Match\Detail\group\all;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Pattern;

class DetailTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        $detail = Pattern::of('(\d+)(?<unit>[cmk]?m)?')->match('12mm, 18km, 17, 19cm')->first();
        // when
        $all = $detail->group('unit')->all();
        // then
        $this->assertSame(['mm', 'km', null, 'cm'], $all);
    }

    /**
     * @test
     */
    public function shouldGetAll_emptyString()
    {
        // when
        $detail = pattern('Hello (?<one>there|here|)')->match('Hello there, General Kenobi, maybe Hello and Hello here')->first();
        // when, then
        $this->assertSame(['Hello there', 'Hello ', 'Hello here'], $detail->all());
        $this->assertSame(['there', '', 'here'], $detail->group('one')->all());
    }

    /**
     * @test
     */
    public function shouldThrow_forNonexistentGroup()
    {
        // given
        $detail = Pattern::of('Yikes!')->match('Yikes!')->first();
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");
        // when
        $detail->group('missing')->all();
    }
}
