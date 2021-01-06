<?php
namespace Test\Interaction\TRegx\CleanRegex\Internal\Prepared\Parser;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Prepared\Parser\FormatParser;
use TRegx\CleanRegex\Internal\Prepared\Parser\Parser;
use TRegx\CleanRegex\Internal\Prepared\PrepareFacade;

class FormatParserTest extends TestCase
{
    /**
     * @test
     */
    public function shouldFormat()
    {
        // given
        $pattern = new FormatParser('My(super)pattern:{%s.%d}', []);

        // when
        $pattern = $this->build($pattern);

        // then
        $this->assertSame('/My\(super\)pattern\:\{%s\.%d\}/', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuildWithTokens()
    {
        // given
        $pattern = new FormatParser('(super):{%s.%d.%%}', [
            '%s' => '\s+\w+',
            '%d' => '\d+',
            '%%' => '%'
        ]);

        // when
        $pattern = $this->build($pattern);

        // then
        $this->assertSame('/\(super\)\:\{\s+\w+\.\d+\.%\}/', $pattern);
    }

    /**
     * @test
     */
    public function shouldPreferEarlierTokens()
    {
        // given
        $pattern = new FormatParser('%%s', ['%s' => 'XXX', '%%' => '%']);

        // when
        $pattern = $this->build($pattern);

        // then
        $this->assertSame('/%s/', $pattern);
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidSubPattern()
    {
        // given
        $pattern = new FormatParser('', [
            ''   => 'XXX',
            '%%' => 'invalid)',
        ]);

        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Malformed pattern 'invalid)' assigned to placeholder '%%'");

        // when
        $this->build($pattern);
    }

    /**
     * @test
     */
    public function shouldThrowForEmptyPlaceholder()
    {
        // given
        $pattern = new FormatParser('', ['' => 'XXX']);

        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Placeholder cannot be empty, must consist of at least one character");

        // when
        $this->build($pattern);
    }

    /**
     * @test
     */
    public function shouldAcceptIntegerKeys()
    {
        // given
        $pattern = new FormatParser('123', [
            1 => '\s',
            2 => '\w'
        ]);

        // when
        $pattern = $this->build($pattern);

        // then
        $this->assertSame('/\s\w3/', $pattern);
    }

    /**
     * @test
     */
    public function shouldDelimiter()
    {
        // given
        $pattern = new FormatParser('%^', [
            '%' => '[/]',
            '^' => '#'
        ]);

        // when
        $pattern = $this->build($pattern);

        // then
        $this->assertSame('%[/]#%', $pattern);
    }

    private function build(Parser $parser): string
    {
        return (new PrepareFacade($parser, false, ''))->getPattern();
    }
}
