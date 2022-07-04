<?php
namespace Test\Feature\CleanRegex\_prepared\builder;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsPattern;
use TRegx\CleanRegex\Pattern;

class PatternTest extends TestCase
{
    use AssertsPattern;

    /**
     * @test
     */
    public function shouldInjectInCommentWithoutExtendedMode()
    {
        // given
        $pattern = Pattern::builder("/#@\n", 'i')->literal('cat~')->build();
        // when, then
        $this->assertConsumesFirst("/#cat~\n", $pattern);
        $this->assertPatternIs("%/#cat~\n%i", $pattern);
    }

    /**
     * @test
     */
    public function shouldNotInjectPlaceholderInCommentExtendedMode()
    {
        // given
        $pattern = Pattern::builder('#@', 'x')->build();
        // when, then
        $this->assertConsumesFirst('', $pattern);
        $this->assertPatternIs('/#@/x', $pattern);
    }
}
