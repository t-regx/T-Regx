<?php
namespace Test\Feature\TRegx\CleanRegex\PatternBuilder;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;
use TRegx\CleanRegex\PatternBuilder;
use TRegx\Exception\MalformedPatternException;

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

    /**
     * @test
     */
    public function shouldThrowMalformedPatternException_forUndelimitedPcrePattern()
    {
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage("No ending delimiter '%' found");

        // when
        Pattern::builder()->pcre()->inject("%Foo", [])->test('bar');
    }
}
