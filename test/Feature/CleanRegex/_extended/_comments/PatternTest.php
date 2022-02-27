<?php
namespace Test\Feature\TRegx\CleanRegex\_extended\_comments;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsPattern;
use TRegx\CleanRegex\Pattern;

class PatternTest extends TestCase
{
    use AssertsPattern;

    /**
     * @test
     */
    public function shouldNotMistakePlaceholderInCommentInExtendedMode()
    {
        // when
        $pattern = Pattern::inject("You/her #@\n her?", [], 'x');

        // then
        $this->assertSamePattern("%You/her #@\n her?%x", $pattern);
    }

    /**
     * @test
     */
    public function shouldUsePlaceholderInCommentInExtendedMode_butExtendedModeIsSwitchedOff()
    {
        // when
        $pattern = Pattern::inject("You/her (?-x:#@\n) her?", ['X'], 'x');

        // then
        $this->assertConsumesFirst("You/her#X\nhe", $pattern);
        $this->assertSamePattern("%You/her (?-x:#X\n) her?%x", $pattern);
    }
}
