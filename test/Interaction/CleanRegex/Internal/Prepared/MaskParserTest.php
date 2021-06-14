<?php
namespace Test\Interaction\TRegx\CleanRegex\Internal\Prepared;

use PHPUnit\Framework\TestCase;
use Test\Utils\Impl\ConstantDelimiter;
use Test\Utils\Impl\NoAlternation;
use TRegx\CleanRegex\Exception\MaskMalformedPatternException;
use TRegx\CleanRegex\Internal\Prepared\MaskParser;
use TRegx\CleanRegex\Internal\Prepared\Parser;
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
        $this->assertSame('My\(super\)pattern\:\{%s\.%d\}', $pattern);
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
        $this->assertSame('\(super\)\:\{\s+\w+\.\d+\.%\}', $pattern);
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
        $this->assertSame('%s', $pattern);
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
        $this->expectException(MaskMalformedPatternException::class);
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
        $this->assertSame('\s\w3', $pattern);
    }

    private function build(Parser $parser): string
    {
        return PrepareFacade::build($parser, new ConstantDelimiter(new NoAlternation()))->delimited();
    }
}
