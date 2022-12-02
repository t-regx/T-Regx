<?php
namespace Test\Feature\CleanRegex\_prepared\template;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsPattern;
use TRegx\CleanRegex\PcrePattern;

class PcrePatternTest extends TestCase
{
    use AssertsPattern;

    /**
     * @test
     * @dataProvider templatesWithPlaceholder
     * @param string $pattern
     * @param string $expected
     */
    public function shouldUsePlaceholder(string $pattern, string $expected)
    {
        // when
        $pattern = PcrePattern::template($pattern)->literal('X');
        // then
        $this->assertPatternIs($expected, $pattern);
    }

    public function templatesWithPlaceholder(): array
    {
        return [
            'standard'                               => ['#You/her @ her?#', '#You/her (?>X) her?#'],
            'comment (but no "x" flag)'              => ["%You/her #@\n her?%", "%You/her #(?>X)\n her?%"],
            'comment ("x" flag, but also "-x" flag)' => ["%You/her (?x:(?-x:#@\n)) her?%", "%You/her (?x:(?-x:#(?>X)\n)) her?%"],
        ];
    }

    /**
     * @test
     */
    public function shouldQuoteUsingDelimiter()
    {
        // given
        $pattern = PcrePattern::template('%foo:@%m')->literal('bar%cat');
        // when, then
        $this->assertPatternIs('%foo:(?>bar\%cat)%m', $pattern);
    }

    /**
     * @test
     */
    public function shouldUsePlaceholderInCommentInExtendedMode_butExtendedModeIsSwitchedOff()
    {
        // when
        $pattern = PcrePattern::template("%You/her (?-x:#@\n) her?%x")->literal('X');
        // then
        $this->assertPatternIs("%You/her (?-x:#(?>X)\n) her?%x", $pattern);
    }
}
