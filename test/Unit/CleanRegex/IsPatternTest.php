<?php
namespace Test\Unit\CleanRegex;

use CleanRegex\Internal\Pattern;
use CleanRegex\IsPattern;
use PHPUnit\Framework\TestCase;

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
}
