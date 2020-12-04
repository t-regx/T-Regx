<?php
namespace Test\Feature\TRegx\CleanRegex;

use PHPUnit\Framework\TestCase;

class SplitPatternTest extends TestCase
{
    /**
     * @test
     */
    public function test()
    {
        // when
        $matches = pattern(',')->split('Foo,Bar,Cat');

        // then
        $this->assertEquals(['Foo', 'Bar', 'Cat'], $matches);
    }

    /**
     * @test
     */
    public function testDelimiter()
    {
        // when
        $matches = pattern('(,)')->split('Foo,Bar,Cat');

        // then
        $this->assertEquals(['Foo', ',', 'Bar', ',', 'Cat'], $matches);
    }
}
