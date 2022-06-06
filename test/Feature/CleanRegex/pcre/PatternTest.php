<?php
namespace Test\Feature\CleanRegex\pcre;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsPattern;
use TRegx\CleanRegex\PcrePattern;

class PatternTest extends TestCase
{
    use AssertsPattern;

    /**
     * @test
     */
    public function shouldBuild_inject()
    {
        // when
        $pattern = PcrePattern::inject('%You/her (are|is) @ %', ['#real?']);
        // then
        $this->assertConsumesFirst('You/her are #real? ', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_template_mask()
    {
        // when
        $pattern = PcrePattern::template('%You/her, @ (her)%s')->mask('%s', ['%s' => '\s']);
        // then
        $this->assertSamePattern('%You/her, \s (her)%s', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_template_literal()
    {
        // when
        $pattern = PcrePattern::template('@You/her, @ (her)@s')->literal('{hi@}');
        // then
        $this->assertSamePattern('@You/her, \{hi\@\} (her)@s', $pattern);
    }

    /**
     * @test
     */
    public function shouldBuild_template_alteration()
    {
        // when
        $pattern = PcrePattern::template('%You/her, @ (her)%s')->alteration(['{hi}', '50%']);
        // then
        $this->assertSamePattern('%You/her, (?:\{hi\}|50\%) (her)%s', $pattern);
    }
}
