<?php
namespace Test\Feature\TRegx\CleanRegex\pcre;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsPattern;
use TRegx\CleanRegex\Pattern;

/**
 * @coversNothing
 */
class PatternTest extends TestCase
{
    use AssertsPattern;

    /**
     * @test
     */
    public function shouldBuild_inject()
    {
        // when
        $pattern = Pattern::pcre()->inject('%You/her (are|is) @ %', ['#real?']);

        // then
        $this->assertConsumesFirst('You/her are #real? ', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_template_mask_build()
    {
        // when
        $pattern = Pattern::pcre()->template('%You/her, @ (her)%s')
            ->mask('%s', ['%s' => '\s'])
            ->build();

        // then
        $this->assertSamePattern('%You/her, \s (her)%s', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_template_literal_build()
    {
        // when
        $pattern = Pattern::pcre()->template('@You/her, @ (her)@s')->literal('{hi@}')->build();

        // then
        $this->assertSamePattern('@You/her, \{hi\@\} (her)@s', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_template_alteration_build()
    {
        // when
        $pattern = Pattern::pcre()->template('%You/her, @ (her)%s')->alteration(['{hi}', '50%'])->build();

        // then
        $this->assertSamePattern('%You/her, (?:\{hi\}|50\%) (her)%s', $pattern);
    }
}
