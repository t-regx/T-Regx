<?php
namespace Test\Feature\CleanRegex\match\Detail\group\matched;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;

class DetailTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeMatched()
    {
        // given
        $detail = Pattern::of('Hello (?<one>there)')->match('Hello there, General Kenobi')->first();
        // when
        $matches = $detail->group('one')->matched();
        // then
        $this->assertTrue($matches);
    }

    /**
     * @test
     */
    public function shouldNotBeMatched_forUnmatchedGroup()
    {
        // given
        $detail = Pattern::of('Hello (?<one>there)?(?<two>XX)')->match('Hello XX, General Kenobi')->first();
        // when
        $matches = $detail->group('one')->matched();
        // then
        $this->assertFalse($matches);
    }

    /**
     * @test
     */
    public function shouldNotBeMatched_forUnmatchedGroup_last()
    {
        // given
        $detail = Pattern::of('Hello (?<one>there)?')->match('Hello XX, General Kenobi')->first();
        // when
        $matches = $detail->group('one')->matched();
        // then
        $this->assertFalse($matches);
    }
}
