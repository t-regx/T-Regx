<?php
namespace Test\Feature\TRegx\CleanRegex\Builder\PatternBuilder;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\FormatMalformedPatternException;
use TRegx\CleanRegex\Pattern;

class PatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBuild_prepare()
    {
        // given
        $pattern = Pattern::prepare(['You/&her, (are|is) ', ['real? (or are you not real?)'], ' (you|her)']);

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertSame('#You/&her, (are|is) real\?\ \(or\ are\ you\ not\ real\?\) (you|her)#', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_bind()
    {
        // given
        $pattern = Pattern::bind('You/&her, (are|is) @question (you|her)', [
            'question' => 'real? (or are you not real?)'
        ]);

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertSame('#You/&her, (are|is) real\?\ \(or\ are\ you\ not\ real\?\) (you|her)#', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_inject()
    {
        // given
        $pattern = Pattern::inject('You/&her, (are|is) @ (you|her)', [
            'real? (or are you not real?)'
        ]);

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertSame('#You/&her, (are|is) real\?\ \(or\ are\ you\ not\ real\?\) (you|her)#', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_compose()
    {
        // given
        $pattern = Pattern::compose([
            pattern('^Fro'),
            Pattern::of('rod'),
            Pattern::pcre('/do$/'),
        ]);

        // when
        $matches = $pattern->allMatch('Frodo');

        // then
        $this->assertTrue($matches);
    }

    /**
     * @test
     */
    public function shouldBuild_mask(): void
    {
        // given
        $pattern = Pattern::mask('%%:%e%w:%c', [
            '%%' => '%',
            '%e' => '\\/',
            '%w' => '\s*',
            '%c' => '.',
        ], 's');

        // when
        $delimited = $pattern->delimited();

        // then
        $this->assertSame('#%\:\\/\s*\:.#s', $delimited);
    }

    /**
     * @test
     */
    public function shouldBuild_mask_Trailing(): void
    {
        // then
        $this->expectException(FormatMalformedPatternException::class);
        $this->expectExceptionMessage("Malformed pattern '\' assigned to keyword '%e'");

        // when
        Pattern::mask('%e', ['%e' => '\\']);
    }

    /**
     * @test
     */
    public function shouldBuild_mask_QuotedTrailing(): void
    {
        // then
        $this->expectException(FormatMalformedPatternException::class);
        $this->expectExceptionMessage("Malformed pattern '\' assigned to keyword '%e'");

        // when
        Pattern::mask('%e', ['%e' => '\\', '%f' => 'e']);
    }

    /**
     * @test
     */
    public function shouldBuild_template_putLiteral_putMask_putLiteral_build(): void
    {
        // given
        $pattern = Pattern::template('^& v&s. &$ @ or `s`', 'i')
            ->putLiteral('&')
            ->putMask('This-is: %3 pattern %4', [
                '%3' => 'x{3,}',
                '%4' => 'x{4,}',
            ])
            ->putLiteral('&')
            ->build();

        // when
        $delimited = $pattern->delimited();

        // then
        $this->assertSame('/^& vThis\-is\:\ x{3,}\ pattern\ x{4,}s. &$ @ or `s`/i', $delimited);
    }

    /**
     * @test
     */
    public function shouldBuild_template_putMask_putLiteral_putMask_build(): void
    {
        // given
        $pattern = Pattern::template('^& v&s. &$ @ or `s`', 'i')
            ->putMask('This-is: %3 pattern %4', [
                '%3' => 'x{3,}',
                '%4' => 'x{4,}',
            ])
            ->putLiteral('&')
            ->putMask('(%e:%%e)', [
                '%%' => '%',
                '%e' => 'e{2,3}'
            ])
            ->build();

        // when
        $delimited = $pattern->delimited();

        // then
        $this->assertSame('/^This\-is\:\ x{3,}\ pattern\ x{4,} v&s. \(e{2,3}\:%e\)$ @ or `s`/i', $delimited);
    }

    /**
     * @test
     */
    public function shouldBuild_template_putMask_inject(): void
    {
        // given
        $pattern = Pattern::template('^& vs. @:@$', 's')
            ->putMask('This-is: %3', ['%3' => 'x{3,}'])
            ->inject(['{{{', ')))']);

        // when
        $delimited = $pattern->delimited();

        // then
        $this->assertSame('/^This\-is\:\ x{3,} vs. \{\{\{:\)\)\)$/s', $delimited);
    }

    /**
     * @test
     */
    public function shouldBuild_template_putMask_bind(): void
    {
        // given
        $pattern = Pattern::template('^& vs. @curly:`parent`$', 's')
            ->putMask('This-is: %3', ['%3' => 'x{3,}'])
            ->bind([
                'curly'  => '{{{',
                'parent' => ')))'
            ]);

        // when
        $delimited = $pattern->delimited();

        // then
        $this->assertSame('/^This\-is\:\ x{3,} vs. \{\{\{:\)\)\)$/s', $delimited);
    }

    /**
     * @test
     */
    public function shouldBuild_template_mask(): void
    {
        // given
        $pattern = Pattern::template('^& vs/ @curly:`parent`$', 's')
            ->mask('This-is: %3', ['%3' => 'x{3,}']);

        // when
        $delimited = $pattern->delimited();

        // then
        $this->assertSame('#^This\-is\:\ x{3,} vs/ @curly:`parent`$#s', $delimited);
    }

    /**
     * @test
     */
    public function shouldBuild_template_putLiteral_build(): void
    {
        // given
        $pattern = Pattern::template('^& vs/ $', 's')->putLiteral('&')->build();

        // when
        $delimited = $pattern->delimited();

        // then
        $this->assertSame('#^& vs/ $#s', $delimited);
    }

    /**
     * @test
     */
    public function shouldBuild_template_inject(): void
    {
        // given
        $pattern = Pattern::template('^@ vs/ $', 's')->inject(['*']);

        // when
        $delimited = $pattern->delimited();

        // then
        $this->assertSame('#^\* vs/ $#s', $delimited);
    }

    /**
     * @test
     */
    public function shouldBuild_template_build(): void
    {
        // given
        $pattern = Pattern::template('^@value vs/ $', 's')->bind(['value' => '*']);

        // when
        $delimited = $pattern->delimited();

        // then
        $this->assertSame('#^\* vs/ $#s', $delimited);
    }
}
