<?php
namespace Test\Unit\TRegx\CleanRegex;

use TRegx\CleanRegex\MatchesPattern;
use TRegx\CleanRegex\Internal\InternalPattern;
use PHPUnit\Framework\TestCase;
use Test\ClassWithToString;

class MatchesPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldMatchPattern()
    {
        // given
        $pattern = new MatchesPattern(new InternalPattern('/[a-z]/'), 'welcome');

        // when
        $result = $pattern->matches();

        // then
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function shouldNotMatchPattern()
    {
        // given
        $pattern = new MatchesPattern(new InternalPattern('/^[a-z]+$/'), 'space space');

        // when
        $result = $pattern->matches();

        // then
        $this->assertFalse($result);
    }

    /**
     * @test
     */
    public function shouldIntegerMatchPattern()
    {
        // given
        $pattern = new MatchesPattern(new InternalPattern('/^2$/'), 2);

        // when
        $true = $pattern->matches();

        // then
        $this->assertTrue($true);
    }

    /**
     * @test
     */
    public function shouldIntegerNotMatchPattern()
    {
        // given
        $pattern = new MatchesPattern(new InternalPattern('/^3$/'), 2);

        // when
        $true = $pattern->matches();

        // then
        $this->assertFalse($true);
    }

    /**
     * @test
     */
    public function shouldClassWithToStringMatchPattern()
    {
        // given
        $class = new ClassWithToString("Lara Croft");
        $pattern = new MatchesPattern(new InternalPattern('/^Lara Croft$/'), $class);

        // when
        $true = $pattern->matches();

        // then
        $this->assertTrue($true);
    }

    /**
     * @test
     */
    public function shouldClassWithToStringNotMatchPattern()
    {
        // given
        $class = new ClassWithToString("text");
        $pattern = new MatchesPattern(new InternalPattern('/^other text/'), $class);

        // when
        $true = $pattern->matches();

        // then
        $this->assertFalse($true);
    }
}
