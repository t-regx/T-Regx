<?php
namespace Test\Integration\TRegx\CleanRegex;

use PHPUnit\Framework\TestCase;

class SplitPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSplitWithoutDelimiter()
    {
        // when
        $matches = pattern(',')->split('Foo,Bar,Cat')->ex();

        // then
        $this->assertEquals(['Foo', 'Bar', 'Cat'], $matches);
    }

    /**
     * @test
     */
    public function shouldSplitWithDelimiter()
    {
        // when
        $matches = pattern('(,)')->split('One,Two,Three')->inc();

        // then
        $this->assertEquals(['One', ',', 'Two', ',', 'Three'], $matches);
    }

    /**
     * @test
     */
    public function shouldReturnUnchangedWithoutDelimiter()
    {
        // when
        $matches = pattern('9')->split('Foo,Bar,Cat')->ex();

        // then
        $this->assertEquals(['Foo,Bar,Cat'], $matches);
    }

    /**
     * @test
     */
    public function shouldReturnUnchangedWithDelimiter()
    {
        // when
        $matches = pattern('9')->split('One,Two,Three')->inc();

        // then
        $this->assertEquals(['One,Two,Three'], $matches);
    }
}
