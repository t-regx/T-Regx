<?php
namespace Test\Feature\TRegx\CleanRegex\PatternBuilder;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;

class PatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBuild_prepared()
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
    public function shouldBuild_format(): void
    {
        // given
        $pattern = Pattern::format('%%:%e%w:%c', [
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
    public function shouldBuild_template_formatting_build(): void
    {
        // given
        $pattern = Pattern::template('^& v&s. &$ @ or `s`', 'i')
            ->formatting('This-is: %3 pattern %4', [
                '%3' => 'x{3,}',
                '%4' => 'x{4,}',
            ])
            ->literal()
            ->formatting('(%e:%%e)', [
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
    public function shouldBuild_template_formatting_inject(): void
    {
        // given
        $pattern = Pattern::template('^& vs. @:@$', 's')
            ->formatting('This-is: %3', ['%3' => 'x{3,}'])
            ->inject(['{{{', ')))']);

        // when
        $delimited = $pattern->delimited();

        // then
        $this->assertSame('/^This\-is\:\ x{3,} vs. \{\{\{:\)\)\)$/s', $delimited);
    }

    /**
     * @test
     */
    public function shouldBuild_template_bind(): void
    {
        // given
        $pattern = Pattern::template('^& vs. @curly:`parent`$', 's')
            ->formatting('This-is: %3', ['%3' => 'x{3,}'])
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
    public function shouldBuild_template_format(): void
    {
        // given
        $pattern = Pattern::template('^& vs/ @curly:`parent`$', 's')
            ->format('This-is: %3', ['%3' => 'x{3,}']);

        // when
        $delimited = $pattern->delimited();

        // then
        $this->assertSame('#^This\-is\:\ x{3,} vs/ @curly:`parent`$#s', $delimited);
    }

    /**
     * @test
     */
    public function shouldBuild_template_literal(): void
    {
        // given
        $pattern = Pattern::template('^& vs/ $', 's')->literal()->build();

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
