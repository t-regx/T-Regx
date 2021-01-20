<?php
namespace Test\Feature\TRegx\CleanRegex\PatternBuilder\builder\pcre;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\PatternBuilder;

class PatternBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBuild_prepared()
    {
        // given
        $pattern = PatternBuilder::builder()->pcre()->prepare(['%You/her, (are|is) ', ['real? % (or are you not real?)'], ' (you|her)%']);

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertSame('%You/her, (are|is) real\?\ \%\ \(or\ are\ you\ not\ real\?\) (you|her)%', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_bind()
    {
        // given
        $pattern = PatternBuilder::builder()->pcre()->bind('%You/her, (are|is) @question (you|her)%', [
            'question' => 'real? % (or are you not real?)'
        ]);

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertSame('%You/her, (are|is) real\?\ \%\ \(or\ are\ you\ not\ real\?\) (you|her)%', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_inject()
    {
        // given
        $pattern = PatternBuilder::builder()->pcre()->inject('%You/her, (are|is) @ (you|her)%', [
            'real? % (or are you not real?)'
        ]);

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertSame('%You/her, (are|is) real\?\ \%\ \(or\ are\ you\ not\ real\?\) (you|her)%', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_formatting()
    {
        // given
        $pattern = PatternBuilder::builder()->pcre()->template('%You/her, & (her)%', 's')
            ->formatting('%s', ['%s' => '\s'])
            ->build();

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertSame('%You/her, \s (her)%s', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_literal()
    {
        // given
        $pattern = PatternBuilder::builder()
            ->pcre()
            ->template('%You/her, & (her)%', 's')
            ->literal()
            ->build();

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertSame('%You/her, & (her)%s', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_format()
    {
        // given
        $pattern = PatternBuilder::builder()->pcre()->template('%You/her, & (her)%', 's')->format('%s', ['%s' => '\s']);

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertSame('%You/her, \s (her)%s', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_pcre_template_inject()
    {
        // given
        $pattern = PatternBuilder::builder()->pcre()->template('%You/her, \s (her)%', 's')->inject([]);

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertSame('%You/her, \s (her)%s', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_pcre_template_bind()
    {
        // given
        $pattern = PatternBuilder::builder()->pcre()->template('%You/her, \s (her)%', 's')->bind([]);

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertSame('%You/her, \s (her)%s', $pattern);
    }
}
