<?php
namespace Test\Feature\CleanRegex\_prepared\_newlineConvention;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsPattern;
use TRegx\CleanRegex\Pattern;

class PatternTest extends TestCase
{
    use AssertsPattern;

    /**
     * @test
     */
    public function shouldAcceptPlaceholderAfterClosingCommentNewlineConvention()
    {
        // when
        $pattern = Pattern::inject("(*CR)^#comment@\rvalue:@$", ['figure'], 'x');
        // then
        $this->assertConsumesFirst('value:figure', $pattern);
    }

    /**
     * @test
     */
    public function shouldVerbNotOverrideNewlineConventionLineFeed()
    {
        // when
        $pattern = Pattern::inject("(*NO_AUTO_POSSESS)^#comment@\nvalue:@$", ['figure'], 'x');
        // then
        $this->assertConsumesFirst('value:figure', $pattern);
    }

    /**
     * @test
     */
    public function shouldVerbNotOverrideNewlineConventionCarrigeReturn()
    {
        // when
        $pattern = Pattern::inject("(*NO_AUTO_POSSESS)^#comment@\rvalue:@$", [], 'x');
        // then
        $this->assertPatternIs("/(*NO_AUTO_POSSESS)^#comment@\rvalue:@$/x", $pattern);
    }

    /**
     * @test
     * @dataProvider verbs
     */
    public function shouldAcceptPlaceholderAfterClosingCommentNewlineConventionVerb(string $verb)
    {
        // when
        $pattern = Pattern::inject("$verb(*CR)^#comment@\rvalue:@$", ['figure'], 'x');
        // then
        $this->assertConsumesFirst('value:figure', $pattern);
    }

    public function verbs(): array
    {
        return \named([
            'starting option' => [
                ['(*NO_AUTO_POSSESS)'],
                ['(*NO_START_OPT)'],
                ['(*UCP)'],
                ['(*UTF)'],
            ],
            'option'          => [
                ['(*BSR_ANYCRLF)'],
                ['(*BSR_UNICODE)'],
            ],
            'parametrized'    => [
                ['(*LIMIT_MATCH=0)'],
                ['(*LIMIT_MATCH=1)'],
                ['(*LIMIT_MATCH=20)'],
                ['(*LIMIT_MATCH=99999999)'],
            ],
            'repeated'        => [
                ['(*NO_AUTO_POSSESS)(*NO_AUTO_POSSESS)'],
                ['(*NO_AUTO_POSSESS)(*NO_AUTO_POSSESS)(*UCP)'],
            ],
            'mixed'           => [
                ['(*CR)(*NO_AUTO_POSSESS)'],
                ['(*LF)(*NO_AUTO_POSSESS)'],
            ]
        ]);
    }

    /**
     * @test
     * @dataProvider unknownVerbs
     */
    public function shouldAcceptPlaceholderAfterClosingCommentNewlineConventionVerbUnknown(string $verb)
    {
        // when
        $pattern = Pattern::inject("$verb(*CR)^#@\r@$", ['figure'], 'x');
        // then
        $this->assertPatternIs("/$verb(*CR)^#@\r(?>figure)$/x", $pattern);
    }

    public function unknownVerbs(): array
    {
        return \named([
            'pcre2'   => [
                ['(*NOTEMPTY)'],
                ['(*NOTEMPTY_ATSTART)'],
                ['(*NO_DOTSTAR_ANCHOR)'],
                ['(*NO_JIT)'],
                ['(*LIMIT_DEPTH=99999999)'],
                ['(*LIMIT_HEAP=99999999)'],
                ['(*LIMIT_DEPTH=20)'],
                ['(*LIMIT_HEAP=20)'],
                ['(*LIMIT_DEPTH=1)'],
                ['(*LIMIT_HEAP=1)'],
                ['(*LIMIT_DEPTH=0)'],
                ['(*LIMIT_HEAP=0)'],
            ],
            'unknown' => [
                ['(*UNKNOWN)'],
                ['(*FOO)'],
                ['(*AZ)'],
                ['(*X)'],
            ]
        ]);
    }

    /**
     * @test
     * @dataProvider newLines
     */
    public function shouldAllowConventionsAndVerbs(string $verb, string $newline)
    {
        // when
        $pattern = Pattern::inject("(*CR)(*UCP)$verb^#comment@{$newline}value:@$", ['figure'], 'x');
        // then
        $this->assertConsumesFirst('value:figure', $pattern);
    }

    public function newLines(): array
    {
        return \named([
            ['(*LF)', "\n"],
            ['(*CR)', "\r"],
            ['(*CRLF)', "\r\n"],
        ]);
    }

    /**
     * @test
     * @dataProvider invalidVerbs
     */
    public function shouldAcceptPlaceholderAfterClosingCommentNewlineConventionVerbMalformedOption(string $verb)
    {
        // when
        $pattern = Pattern::inject("$verb(*CR)^#comment@\rvalue:@$", ['figure'], 'x');
        // then
        $this->assertPatternIs("/$verb(*CR)^#comment@\rvalue:(?>figure)$/x", $pattern);
    }

    public function invalidVerbs(): array
    {
        return \named([
            ['(*LIMIT_DEPTH)'],
            ['(*LIMIT_HEAP)'],
            ['(*LIMIT_MATCH)'],
            ['(*LIMIT_DEPTH=)'],
            ['(*LIMIT_HEAP=)'],
            ['(*LIMIT_MATCH=)'],
        ]);
    }

    /**
     * @test
     */
    public function shouldNotAcceptNewlineConventionAfterZeroLengthVerb()
    {
        // when
        $pattern = Pattern::inject("(*)(*CR)^#@\r@$", [], 'x');
        // then
        $this->assertPatternIs("/(*)(*CR)^#@\r@$/x", $pattern);
    }
}
