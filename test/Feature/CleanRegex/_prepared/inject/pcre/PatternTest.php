<?php
namespace Test\Feature\CleanRegex\_prepared\inject\pcre;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsPattern;
use TRegx\CleanRegex\PcrePattern;

class PatternTest extends TestCase
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
        $pattern = PcrePattern::inject($pattern, ['X']);
        // then
        $this->assertSamePattern($expected, $pattern);
    }

    public function templatesWithPlaceholder(): array
    {
        return [
            'standard'                               => ['#You/her @ her?#', '#You/her X her?#'],
            'comment (but no "x" flag)'              => ["%You/her #@\n her?%", "%You/her #X\n her?%"],
            'comment ("x" flag, but also "-x" flag)' => ["%You/her (?x:(?-x:#@\n)) her?%", "%You/her (?x:(?-x:#X\n)) her?%"],
        ];
    }

    /**
     * @test
     * @dataProvider templatesWithoutPlaceholders
     * @param string $pattern
     * @param string $expected
     */
    public function shouldNotMistakeLiteralForPlaceholder(string $pattern, string $expected)
    {
        // when
        $pattern = PcrePattern::inject($pattern, []);
        // then
        $this->assertSamePattern($expected, $pattern);
    }

    public function templatesWithoutPlaceholders(): array
    {
        return [
            "placeholder '@' in []"      => ['#You/her [@] her?#', '#You/her [@] her?#'],
            "placeholder '@' in \Q\E"    => ['#You/her \Q@\E her?#', '#You/her \Q@\E her?#'],
            "placeholder '@' escaped"    => ['#You/her \@ her?#', '#You/her \@ her?#'],
            "placeholder '@' in comment" => ["%You/her (?x:#@\n) her?%", "%You/her (?x:#@\n) her?%"],
            "placeholder '@' in control" => ['#You/her \c@ her?#', '#You/her \c@ her?#'],
        ];
    }

    /**
     * @test
     */
    public function shouldNotMistakePlaceholderInCommentInExtendedMode()
    {
        // when
        $pattern = PcrePattern::inject("%You/her #@\n her?%x", []);
        // then
        $this->assertSamePattern("%You/her #@\n her?%x", $pattern);
    }

    /**
     * @test
     */
    public function shouldUsePlaceholderInCommentInExtendedMode_butExtendedModeIsSwitchedOff()
    {
        // when
        $pattern = PcrePattern::inject("%You/her (?-x:#@\n) her?%x", ['X']);
        // then
        $this->assertSamePattern("%You/her (?-x:#X\n) her?%x", $pattern);
    }
}
