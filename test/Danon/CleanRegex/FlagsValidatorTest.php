<?php
namespace Danon\CleanRegex;

use PHPUnit\Framework\TestCase;

class FlagsValidatorTest extends TestCase
{
    /**
     * @test
     */
    public function shouldAllowFlags()
    {
        // given
        $flags = new FlagsValidator();

        // when
        $flags->validate("mi");

        // then
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function shouldEmptyBeAllowed()
    {
        // given
        $flags = new FlagsValidator();

        // when
        $flags->validate("");

        // then
        $this->assertTrue(true);
    }

    /**
     * @test
     * @expectedException \Danon\CleanRegex\Exception\FlagNotAllowedException
     */
    public function shouldNotAllowWhitespace()
    {
        // given
        $flags = new FlagsValidator();

        // when
        $flags->validate("g i");
    }

    /**
     * @test
     * @dataProvider invalidFlags
     * @expectedException \Danon\CleanRegex\Exception\FlagNotAllowedException
     */
    public function shouldNotAllowFlags()
    {
        // given
        $flags = new FlagsValidator();

        // when
        $flags->validate(" ");
    }

    public function invalidFlags()
    {
        return [
            ['+g'],
            ['-g'],
            ['/'],
            ['G'],
        ];
    }
}
