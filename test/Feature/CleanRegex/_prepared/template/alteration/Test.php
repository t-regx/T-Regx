<?php
namespace Test\Feature\CleanRegex\_prepared\template\alteration;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsPattern;
use TRegx\CleanRegex\Pattern;

class Test extends TestCase
{
    use AssertsPattern;

    /**
     * @test
     */
    public function shouldEscape()
    {
        // given
        $pattern = Pattern::template('/#%@')->alteration(['/()', '^#$']);
        // when, then
        $this->assertPatternIs('~/#%(?:/\(\)|\^\#\$)~', $pattern);
    }

    /**
     * @test
     */
    public function shouldEscapeDelimiter()
    {
        // given
        $pattern = Pattern::template('/#@')->alteration(['a', '%b']);
        // when, then
        $this->assertPatternIs('%/#(?:a|\%b)%', $pattern);
    }

    /**
     * @test
     */
    public function shouldNotPreserveDuplicates()
    {
        // given
        $pattern = Pattern::template('@')->alteration(['a', '', '', 'b', 'a']);
        // when, then
        $this->assertPatternIs('/(?:a||b)/', $pattern);
    }

    /**
     * @test
     */
    public function shouldPreserveFalsyStrings()
    {
        // given
        $pattern = Pattern::template('@')->alteration(['|', ' ', '0']);
        // when, then
        $this->assertPatternIs('/(?:\||\ |0)/', $pattern);
    }

    /**
     * @test
     */
    public function shouldThrowForArrayValues()
    {
        // given
        $template = Pattern::template('@');
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid bound alternate value. Expected string, but array (0) given');
        // when
        $template->alteration(['|', []]);
    }

    /**
     * @test
     */
    public function shouldThrowForIntegerValues()
    {
        // given
        $template = Pattern::template('@');
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid bound alternate value. Expected string, but integer (5) given');
        // when
        $template->alteration(['|', 5]);
    }

    /**
     * @test
     */
    public function shouldThrowForFalse()
    {
        // given
        $template = Pattern::template('@');
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid bound alternate value. Expected string, but boolean (false) given');
        // when
        $template->alteration([false]);
    }
}
