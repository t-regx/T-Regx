<?php
namespace Test\Supposition\lineEndings;

use PHPUnit\Framework\TestCase;
use Test\Supposition\TRegx\lineEndings\LineEndingAssertion;
use Test\Supposition\TRegx\lineEndings\LineEndings;
use Test\Utils\Assertion\AssertsPattern;
use Test\Utils\TestCase\TestCasePasses;
use TRegx\CleanRegex\Exception\PlaceholderFigureException;
use TRegx\CleanRegex\Pattern;

class PreparedTest extends TestCase
{
    use LineEndings, TestCasePasses, AssertsPattern;

    /**
     * @test
     * @dataProvider closingEndings
     */
    public function shouldCloseCommentInGivenConvention(string $convention, Ending $ending)
    {
        $pattern = $this->patternPlaceholder($convention, $ending, 'value');
        $assertion = new LineEndingAssertion($pattern, $ending, $convention);
        $assertion->assertCommentClosed('value');
    }

    /**
     * @test
     * @dataProvider ignoredEndings
     */
    public function shouldNotCloseComment(string $convention, Ending $ending)
    {
        $pattern = $this->patternEmpty($convention, $ending);
        $assertion = new LineEndingAssertion($pattern, $ending, $convention);
        $assertion->assertCommentIgnored('@');
    }

    /**
     * @test
     * @dataProvider prefixedVerbs
     */
    public function shouldIgnoreVerb_NotAtTheStart(string $convention, Ending $ending)
    {
        // when
        $pattern = $this->patternEmpty($convention, $ending);
        // then
        $this->assertPatternIs("/$convention^$#comment{$ending->ending()}@$/uxD", $pattern);
    }

    public function prefixedVerbs(): array
    {
        $prefixedVerbs = [
            'lf' => ['(*CR)prefix (*LF)', new Ending('lf')],
            'cr' => ['(*LF)prefix (*CR)', new Ending('cr')],
        ];

        // We need (*NUL) for this test, because that's the only convention
        // in which CRLF won't close the subject
        $prefixedVerbsNull = [
            'crlf'       => ['(*NUL)prefix (*CRLF)', new Ending('crlf')],
            'anycrlf,nl' => ['(*NUL)prefix (*ANYCRLF)', new Ending('nl')],
            'anycrlf,ls' => ['(*NUL)prefix (*ANYCRLF)', new Ending('ls')],
            'anycrlf,ps' => ['(*NUL)prefix (*ANYCRLF)', new Ending('ps')],

            'any,crlf' => ['(*NUL)prefix (*ANY)', new Ending('crlf')],
            'any,ff'   => ['(*NUL)prefix (*ANY)', new Ending('ff')],
            'any,ls'   => ['(*NUL)prefix (*ANY)', new Ending('ls')],
            'any,ps'   => ['(*NUL)prefix (*ANY)', new Ending('ps')],
        ];

        if (PHP_VERSION_ID < 70400) {
            return $prefixedVerbs;
        }
        return $prefixedVerbs + $prefixedVerbsNull;
    }

    private function patternEmpty(string $convention, Ending $ending): Pattern
    {
        try {
            return Pattern::inject("$convention^$#comment{$ending->ending()}@$", [], 'uxD');
        } catch (PlaceholderFigureException $exception) {
            $this->fail("Failed to assert that ending '$ending' did not close the comment with $convention - placeholder was treated as if outside the comment");
        }
    }

    private function patternPlaceholder(string $convention, Ending $ending, string $figure): Pattern
    {
        try {
            return Pattern::inject("$convention^#comment{$ending->ending()}@$", [$figure], 'uxD');
        } catch (PlaceholderFigureException $exception) {
            $this->fail("Failed to assert that ending '$ending' closed the comment with $convention - placeholder was treated as the comment");
        }
    }
}
