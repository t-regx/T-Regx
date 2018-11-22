<?php
namespace Test\Integration\TRegx\CleanRegex;

use PHPUnit\Framework\TestCase;

class SplitPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSplit_ex()
    {
        // when
        $matches = pattern(',')->split('Foo,Bar,Cat')->ex();

        // then
        $this->assertEquals(['Foo', 'Bar', 'Cat'], $matches);
    }

    /**
     * @test
     */
    public function shouldSplit_inc()
    {
        // when
        $matches = pattern('(,)')->split('One,Two,Three')->inc();

        // then
        $this->assertEquals(['One', ',', 'Two', ',', 'Three'], $matches);
    }

    /**
     * @test
     */
    public function shouldSplit_filter_ex()
    {
        // when
        $matches = pattern(',')->split('Foo,,,Bar,,,Cat')->filter()->ex();

        // then
        $this->assertEquals(['Foo', 'Bar', 'Cat'], $matches);
    }

    /**
     * @test
     */
    public function shouldSplit_filter_inc()
    {
        // when
        $matches = pattern('(,)')->split('One,,Two,,Three')->filter()->inc();

        // then
        $this->assertEquals(['One', ',', ',', 'Two', ',', ',', 'Three'], $matches);
    }
}
