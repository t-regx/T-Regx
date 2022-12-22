<?php
namespace Test\Feature\CleanRegex\_prepared\builder;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsPattern;
use TRegx\CleanRegex\Exception\MaskMalformedPatternException;
use TRegx\CleanRegex\PcrePattern;

class PcrePatternTest extends TestCase
{
    use AssertsPattern;

    /**
     * @test
     * @dataProvider templatesWithoutPlaceholders
     * @param string $pattern
     * @param string $expected
     */
    public function shouldNotMistakeLiteralForPlaceholder(string $pattern, string $expected)
    {
        // when
        $pattern = PcrePattern::builder($pattern)->build();
        // then
        $this->assertPatternIs($expected, $pattern);
    }

    public function templatesWithoutPlaceholders(): array
    {
        return [
            "placeholder '@' in []"      => ['#You/her [@] her?#', '#You/her [@] her?#'],
            "placeholder '@' in ["       => ['#You/her [@ her?#', '#You/her [@ her?#'],
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
        $pattern = PcrePattern::builder("%You/her #@\n her?%x")->build();
        // then
        $this->assertPatternIs("%You/her #@\n her?%x", $pattern);
    }

    /**
     * @test
     */
    public function shouldValidateMaskWithFlags()
    {
        // given
        $template = PcrePattern::builder('%^@$%x');
        // then
        $this->expectException(MaskMalformedPatternException::class);
        $this->expectExceptionMessage("Malformed pattern '#commen(t\nfoo)' assigned to keyword '*'");
        // when, then
        $template->mask('*', ['*' => "#commen(t\nfoo)"])->build();
    }
}
