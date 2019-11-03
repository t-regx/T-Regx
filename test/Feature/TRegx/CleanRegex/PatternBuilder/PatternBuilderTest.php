<?php
namespace Test\Feature\TRegx\CleanRegex\PatternBuilder;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;
use TRegx\CleanRegex\PatternBuilder;

class PatternBuilderTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBuild_compose()
    {
        // given
        $pattern = PatternBuilder::compose([
            pattern('^Fro'),
            Pattern::of('rod'),
            Pattern::pcre('/do$/')
        ]);

        // when
        $matches = $pattern->allMatch('Frodo');

        // then
        $this->assertTrue($matches);
    }
}
