<?php
namespace Test\Unit\TRegx\CleanRegex\Internal;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\Flags;

/**
 * @covers \TRegx\CleanRegex\Internal\Flags
 */
class FlagsTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCastToStringWithoutDuplicates()
    {
        // given
        $flags = new Flags('hello');

        // then
        $this->assertSame('helo', "$flags");
    }

    /**
     * @test
     */
    public function shouldRemoveFlags()
    {
        // given
        $flags = new Flags('hello');

        // when
        $newFlags = $flags->remove('le');

        // then
        $this->assertEquals(new Flags('ho'), $newFlags);
    }

    /**
     * @test
     */
    public function shouldAppendFlags()
    {
        // given
        $flags = new Flags('hello');

        // when
        $newFlags = $flags->append('lke');

        // then
        $this->assertEquals(new Flags('hellolke'), $newFlags);
    }

    /**
     * @test
     */
    public function shouldParseFlags()
    {
        // when
        [$constructive, $destructive] = Flags::parse('heello-ehh-o');

        // then
        $this->assertEquals(new Flags('heello'), $constructive);
        $this->assertEquals(new Flags('ehho'), $destructive);
    }

    /**
     * @test
     */
    public function shouldParseFlagsDestructive()
    {
        // when
        [$constructive, $destructive] = Flags::parse('-ehh--o');

        // then
        $this->assertEquals(new Flags(''), $constructive);
        $this->assertEquals(new Flags('ehho'), $destructive);
    }

    /**
     * @test
     */
    public function shouldParseFlagsEmpty()
    {
        // when
        [$constructive, $destructive] = Flags::parse('-');

        // then
        $this->assertEquals(new Flags(''), $constructive);
        $this->assertEquals(new Flags(''), $destructive);
    }

    /**
     * @test
     */
    public function shouldNotHaveFlag()
    {
        // given
        $flags = new Flags('x');

        // when
        $has = $flags->has('d');

        // then
        $this->assertFalse($has);
    }

    /**
     * @test
     */
    public function shouldHaveFlag()
    {
        // given
        $flags = new Flags('bxd');

        // when
        $has = $flags->has('x');

        // then
        $this->assertTrue($has);
    }

    /**
     * @test
     */
    public function shouldHaveFlagFirst()
    {
        // given
        $flags = new Flags('bxd');

        // when
        $has = $flags->has('b');

        // then
        $this->assertTrue($has);
    }

    /**
     * @test
     */
    public function shouldThrowForEmptyFlags()
    {
        // given
        $flags = new Flags('bxd');

        // then
        $this->expectException(InternalCleanRegexException::class);

        // when
        $flags->has('');
    }

    /**
     * @test
     */
    public function shouldThrowForTwoFlags()
    {
        // given
        $flags = new Flags('bxd');

        // then
        $this->expectException(InternalCleanRegexException::class);

        // when
        $flags->has('ab');
    }
}
