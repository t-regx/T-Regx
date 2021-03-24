<?php
namespace Test\Feature\TRegx\CleanRegex\Builder\PatternBuilder\builder\pcre;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;

class PatternBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBuild_prepared()
    {
        // given
        $pattern = Pattern::builder()->pcre()->prepare(['%You/her, (are|is) ', ['real? % (or are you not real?)'], ' (you|her)%']);

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
        $pattern = Pattern::builder()->pcre()->bind('%You/her, (are|is) @question (you|her)%', [
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
        $pattern = Pattern::builder()->pcre()->inject('%You/her, (are|is) @ (you|her)%', [
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
    public function shouldBuild_template_builder_mask_build()
    {
        // given
        $pattern = Pattern::builder()->pcre()->template('%You/her, & (her)%s')
            ->builder()
            ->mask('%s', ['%s' => '\s'])
            ->build();

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertSame('%You/her, \s (her)%s', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_template_builder_literal_build()
    {
        // given
        $pattern = Pattern::builder()
            ->pcre()
            ->template('%You/her, & (her)%s')
            ->builder()
            ->literal('{hi}')
            ->build();

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertSame('%You/her, \{hi\} (her)%s', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_template_mask()
    {
        // given
        $pattern = Pattern::builder()->pcre()->template('%You/her, & (her)%s')->mask('%s', ['%s' => '\s']);

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertSame('%You/her, \s (her)%s', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_template_literal()
    {
        // given
        $pattern = Pattern::builder()->pcre()->template('%You/her, & (her)%s')->literal('\s');

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertSame('%You/her, \\\\s (her)%s', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_template_inject()
    {
        // given
        $pattern = Pattern::builder()->pcre()->template('%You/her, \s (her)%s')->inject([]);

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertSame('%You/her, \s (her)%s', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_template_bind()
    {
        // given
        $pattern = Pattern::builder()->pcre()->template('%You/her, \s (her)%s')->bind([]);

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertSame('%You/her, \s (her)%s', $pattern);
    }
}
