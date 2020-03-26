<?php
namespace Test\Unit\TRegx\CleanRegex\Internal;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\DuplicateFlagsException;
use TRegx\CleanRegex\Exception\FlagNotAllowedException;
use TRegx\CleanRegex\Internal\FlagsValidator;

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
        $flags->validate('imxsuUXAJDS');

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
        $flags->validate('');

        // then
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function shouldNotAllowWhitespace()
    {
        // given
        $flags = new FlagsValidator();

        // then
        $this->expectException(FlagNotAllowedException::class);
        $this->expectExceptionMessage("Regular expression flag: ' ' is not allowed");

        // when
        $flags->validate(' i');
    }

    /**
     * @test
     * @dataProvider invalidFlags
     * @param string $flag
     * @param string $message
     * @throws FlagNotAllowedException
     */
    public function shouldNotAllowInvalidFlags(string $flag, string $message)
    {
        // given
        $flags = new FlagsValidator();

        // then
        $this->expectException(FlagNotAllowedException::class);
        $this->expectExceptionMessage($message);

        // when
        $flags->validate($flag);
    }

    public function invalidFlags()
    {
        return [
            ['+g', "Regular expression flags: ['+', 'g'] are not allowed"],
            ['-g', "Regular expression flags: ['-', 'g'] are not allowed"],
            ['/', "Regular expression flag: '/' is not allowed"],
            ['G', "Regular expression flag: 'G' is not allowed"],
        ];
    }

    /**
     * @test
     */
    public function shouldThrowForDuplicateFlags()
    {
        // given
        $flags = new FlagsValidator();

        // then
        $this->expectException(DuplicateFlagsException::class);
        $this->expectExceptionMessage("Regular expression flag: 'm' is duplicated in 'UmDmS'");

        // when
        $flags->validate('UmDmS');
    }

    /**
     * @test
     */
    public function shouldValidateMultipleFlags()
    {
        // given
        $flags = new FlagsValidator();

        // then
        $this->expectException(FlagNotAllowedException::class);
        $this->expectExceptionMessage("Regular expression flags: ['f', 'c'] are not allowed");

        // when
        $flags->validate('fc');
    }
}
