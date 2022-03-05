<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Parser;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Prepared\Parser\SubpatternFlags;

/**
 * @covers \TRegx\CleanRegex\Internal\Flags
 */
class SubpatternFlagsTest extends TestCase
{
    /**
     * @test
     */
    public function shouldRemoveFlags()
    {
        // given
        $flags = new SubpatternFlags('hello');
        // when
        $newFlags = $flags->remove(new SubpatternFlags('le'));
        // then
        $this->assertEquals(new SubpatternFlags('ho'), $newFlags);
    }

    /**
     * @test
     */
    public function shouldAppendFlags()
    {
        // given
        $flags = new SubpatternFlags('hello');
        // when
        $newFlags = $flags->append(new SubpatternFlags('lke'));
        // then
        $this->assertEquals(new SubpatternFlags('hellolke'), $newFlags);
    }

    /**
     * @test
     */
    public function shouldParseFlags()
    {
        // when
        [$constructive, $destructive] = SubpatternFlags::parse('heello-ehh-o');
        // then
        $this->assertEquals(new SubpatternFlags('heello'), $constructive);
        $this->assertEquals(new SubpatternFlags('ehho'), $destructive);
    }

    /**
     * @test
     */
    public function shouldParseFlagsDestructive()
    {
        // when
        [$constructive, $destructive] = SubpatternFlags::parse('-ehh--o');

        // then
        $this->assertEquals(new SubpatternFlags(''), $constructive);
        $this->assertEquals(new SubpatternFlags('ehho'), $destructive);
    }

    /**
     * @test
     */
    public function shouldParseFlagsEmpty()
    {
        // when
        [$constructive, $destructive] = SubpatternFlags::parse('-');
        // then
        $this->assertEquals(new SubpatternFlags(''), $constructive);
        $this->assertEquals(new SubpatternFlags(''), $destructive);
    }

    /**
     * @test
     */
    public function shouldNotHaveFlag()
    {
        // given
        $flags = new SubpatternFlags('x');
        // when + then
        $this->assertFalse($flags->has('d'));
    }

    /**
     * @test
     */
    public function shouldHaveFlag()
    {
        // given
        $flags = new SubpatternFlags('bxd');
        // when + then
        $this->assertTrue($flags->has('x'));
    }

    /**
     * @test
     */
    public function shouldHaveFlagFirst()
    {
        // given
        $flags = new SubpatternFlags('bxd');
        // when + then
        $this->assertTrue($flags->has('b'));
    }
}
