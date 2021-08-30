<?php
namespace Test\Feature\TRegx\CleanRegex\Builder\PatternBuilder\pcre;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;

/**
 * @coversNothing
 */
class PatternBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBuild_inject()
    {
        // given
        $value = 'real? % (or are you not real?)';
        $pattern = Pattern::pcre()->inject('%You/her, (are|is) @ (you|her)%', [$value]);

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertSame('%You/her, (are|is) real\?\ \%\ \(or\ are\ you\ not\ real\?\) (you|her)%', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_template_mask_build()
    {
        // given
        $pattern = Pattern::pcre()->template('%You/her, @ (her)%s')
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
    public function shouldBuild_template_literal_build()
    {
        // given
        $pattern = Pattern::pcre()->template('@You/her, @ (her)@s')->literal('{hi@}')->build();

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertSame('@You/her, \{hi\@\} (her)@s', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_template_alteration_build()
    {
        // given
        $pattern = Pattern::pcre()->template('%You/her, @ (her)%s')->alteration(['{hi}', '50%'])->build();

        // when
        $pattern = $pattern->delimited();

        // then
        $this->assertSame('%You/her, (?:\{hi\}|50\%) (her)%s', $pattern);
    }
}
