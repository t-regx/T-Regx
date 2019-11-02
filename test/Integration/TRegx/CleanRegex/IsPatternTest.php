<?php
namespace Test\Integration\TRegx\CleanRegex;

use PHPUnit\Framework\TestCase;
use Test\Utils\PhpVersionDependent;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\IsPattern;
use TRegx\SafeRegex\Exception\MalformedPatternException;

class IsPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeValid()
    {
        // given
        $is = new IsPattern(InternalPattern::manual('/valid/'));

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
        $is = new IsPattern(InternalPattern::manual('/invalid)'));

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
        $is = new IsPattern(InternalPattern::manual('/valid/'));

        // when
        $valid = $is->usable();

        // then
        $this->assertTrue($valid);
    }

    /**
     * @test
     */
    public function shouldBeUsable_notDelimited()
    {
        // given
        $is = new IsPattern(InternalPattern::automatic('valid'));

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
        $is = new IsPattern(InternalPattern::manual('/invalid)/'));

        // when
        $valid = $is->usable();

        // then
        $this->assertFalse($valid);
    }

    /**
     * @test
     */
    public function shouldNotBeUsable_invalid_notDelimited()
    {
        // given
        $is = new IsPattern(InternalPattern::manual('invalid)'));

        // when
        $usable = $is->usable();

        // then
        $this->assertFalse($usable);
    }

    /**
     * @test
     */
    public function shouldBeDelimited()
    {
        // given
        $is = new IsPattern(InternalPattern::manual('/valid/'));

        // when
        $delimited = $is->delimited();

        // then
        $this->assertTrue($delimited);
    }

    /**
     * @test
     */
    public function shouldNotBeDelimited()
    {
        // given
        $is = new IsPattern(InternalPattern::automatic('/invalid'));

        // when
        $delimited = $is->delimited();

        // then
        $this->assertFalse($delimited);
    }

    /**
     * @test
     */
    public function shouldThrow_onInvalidPattern()
    {
        // given
        $is = new IsPattern(InternalPattern::automatic('invalid)'));

        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessageRegExp(PhpVersionDependent::getUnmatchedParenthesisMessage(7));

        // when
        $is->delimited();
    }
}
