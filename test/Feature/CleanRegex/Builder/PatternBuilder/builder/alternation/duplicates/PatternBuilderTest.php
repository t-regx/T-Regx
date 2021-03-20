<?php
namespace Test\Feature\TRegx\CleanRegex\Builder\PatternBuilder\builder\alternation\duplicates;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;

class PatternBuilderTest extends TestCase
{
    /**
     * @test
     * @dataProvider flagsAndAlternationResults
     * @param string $flags
     * @param string $expected
     */
    public function shouldBuild_bind(string $flags, string $expected)
    {
        // given
        $pattern = Pattern::bind('Foo@bar', ['bar' => $this->alternation()], $flags);

        // when
        $patter = $pattern->delimited();

        // then
        $this->assertSame("/Foo(?:$expected)/$flags", $patter);
    }

    /**
     * @test
     * @dataProvider flagsAndAlternationResults
     * @param string $flags
     * @param string $expected
     */
    public function shouldBuild_inject(string $flags, string $expected)
    {
        // given
        $pattern = Pattern::inject('Foo@', [$this->alternation()], $flags);

        // when
        $patter = $pattern->delimited();

        // then
        $this->assertSame("/Foo(?:$expected)/$flags", $patter);
    }

    public function flagsAndAlternationResults(): array
    {
        return [
            'no flags'  => ['', 'Foo|foo|łóżko|ŁÓŻKO'],
            'flags: i'  => ['i', 'Foo|łóżko|ŁÓŻKO'],
            'flags: ui' => ['ui', 'Foo|łóżko']
        ];
    }

    private function alternation(): array
    {
        return ['Foo', 'foo', 'łóżko', 'ŁÓŻKO'];
    }
}
