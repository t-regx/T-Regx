<?php
namespace Test\Unit\Pattern;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;

class groupCount extends TestCase
{
    public function test()
    {
        $pattern = new Pattern('(group) (?<name>named group)');
        $this->assertSame(2, $pattern->groupCount());
    }

    /**
     * @test
     */
    public function empty()
    {
        $pattern = new Pattern('pattern');
        $this->assertSame(0, $pattern->groupCount());
    }

    /**
     * @test
     */
    public function indexed()
    {
        $pattern = new Pattern('(group) (second group)');
        $this->assertSame(2, $pattern->groupCount());
    }

    /**
     * @test
     */
    public function named()
    {
        $pattern = new Pattern('(?<first>) (?<second>)');
        $this->assertSame(2, $pattern->groupCount());
    }
}
