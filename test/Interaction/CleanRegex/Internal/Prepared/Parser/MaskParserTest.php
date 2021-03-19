<?php
namespace Test\Interaction\TRegx\CleanRegex\Internal\Prepared\Parser;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\FormatMalformedPatternException;
use TRegx\CleanRegex\Internal\Prepared\Parser\MaskParser;
use TRegx\CleanRegex\Internal\Prepared\Parser\Parser;
use TRegx\CleanRegex\Internal\Prepared\PrepareFacade;

class MaskParserTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBuildMask()
    {
        // given
        $pattern = new MaskParser('My(super)pattern:{%s.%d}', []);

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
        $pattern = new MaskParser('(super):{%s.%d.%%}', [
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
        $pattern = new MaskParser('%%s', ['%s' => 'XXX', '%%' => '%']);

        // when
        $pattern = $this->build($pattern);

        // then
        $this->assertSame('/%s/', $pattern);
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidSubPatternFirst()
    {
        // given
        $pattern = new MaskParser('', [
            ''   => 'XXX',
            '%%' => 'invalid)',
        ]);

        // then
        $this->expectException(FormatMalformedPatternException::class);
        $this->expectExceptionMessage("Malformed pattern 'invalid)' assigned to keyword '%%'");

        // when
        $this->build($pattern);
    }

    /**
     * @test
     */
    public function shouldThrowForEmptyKeyword()
    {
        // given
        $pattern = new MaskParser('', ['' => 'XXX']);

        // then
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Keyword cannot be empty, must consist of at least one character');

        // when
        $this->build($pattern);
    }

    /**
     * @test
     */
    public function shouldAcceptIntegerKeys()
    {
        // given
        $pattern = new MaskParser('123', [
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
        $pattern = new MaskParser('%^', [
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
