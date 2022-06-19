<?php
namespace Test\Supposition\lineEndings;

use PHPUnit\Framework\TestCase;
use Test\Supposition\TRegx\lineEndings\LineEndingAssertion;
use Test\Supposition\TRegx\lineEndings\LineEndings;
use TRegx\CleanRegex\Pattern;

class StandardTest extends TestCase
{
    use LineEndings;

    /**
     * @test
     * @dataProvider closingEndings
     */
    public function shouldCloseCommentInGivenConvention(string $convention, Ending $ending)
    {
        // given
        $pattern = Pattern::of("$convention^#comment{$ending->ending()}value$", 'uxD');
        $assertion = new LineEndingAssertion($ending, $convention, $pattern);
        // when, then
        $assertion->assertCommentClosed('value');
    }

    /**
     * @test
     * @dataProvider ignoredEndings
     */
    public function shouldNotCloseComment(string $convention, Ending $ending)
    {
        // given
        $pattern = Pattern::of("$convention^$#comment{$ending->ending()}value$", 'uxD');
        $assertion = new LineEndingAssertion($ending, $convention, $pattern);
        // when, then
        $assertion->assertCommentIgnored('value');
    }
}
