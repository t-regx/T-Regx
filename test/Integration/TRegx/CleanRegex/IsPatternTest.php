<?php
namespace Test\Integration\TRegx\CleanRegex;

use PHPUnit\Framework\TestCase;
use Test\Utils\PhpVersionDependent;
use TRegx\CleanRegex\Exception\CleanRegex\InvalidPatternException;
use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\CleanRegex\IsPattern;

class IsPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeValid()
    {
        // given
        $is = new IsPattern(new Pattern('/valid/'));

        // when
        $valid = $is->valid();

        // then
        $this->assertTrue($valid);
    }

    /**
     * @test
     */
    public function shouldNotBeValid()
    {
        // given
        $is = new IsPattern(new Pattern('/invalid)'));

        // when
        $valid = $is->valid();

        // then
        $this->assertFalse($valid);
    }

    /**
     * @test
     */
    public function shouldBeUsable_valid()
    {
        // given
        $is = new IsPattern(new Pattern('/valid/'));

        // when
        $valid = $is->usable();

        // then
        $this->assertTrue($valid);
    }

    /**
     * @test
     */
    public function shouldBeUsable_notDelimitered()
    {
        // given
        $is = new IsPattern(new Pattern('valid'));

        // when
        $usable = $is->usable();

        // then
        $this->assertTrue($usable);
    }

    /**
     * @test
     */
    public function shouldNotBeUsable_invalid()
    {
        // given
        $is = new IsPattern(new Pattern('/invalid)/'));

        // when
        $valid = $is->usable();

        // then
        $this->assertFalse($valid);
    }

    /**
     * @test
     */
    public function shouldNotBeUsable_invalid_notDelimitered()
    {
        // given
        $is = new IsPattern(new Pattern('invalid)'));

        // when
        $usable = $is->usable();

        // then
        $this->assertFalse($usable);
    }

    /**
     * @test
     */
    public function shouldBeDelimitered()
    {
        // given
        $is = new IsPattern(new Pattern('/valid/'));

        // when
        $delimitered = $is->delimitered();

        // then
        $this->assertTrue($delimitered);
    }

    /**
     * @test
     */
    public function shouldNotBeDelimitered()
    {
        // given
        $is = new IsPattern(new Pattern('/invalid'));

        // when
        $delimitered = $is->delimitered();

        // then
        $this->assertFalse($delimitered);
    }

    /**
     * @test
     */
    public function shouldThrow_onInvalidPattern()
    {
        // given
        $is = new IsPattern(new Pattern('invalid)'));

        // then
        $this->expectException(InvalidPatternException::class);
        $this->expectExceptionMessageRegExp(PhpVersionDependent::getUnmatchedParenthesisMessage(7));

        // when
        $is->delimitered();
    }
}
