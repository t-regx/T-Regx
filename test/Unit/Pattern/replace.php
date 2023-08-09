<?php
namespace Test\Unit\Pattern;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;

class replace extends TestCase
{
    public function test()
    {
        $pattern = new Pattern('do|not');
        $this->assertSame('We . . sow', $pattern->replace('We do not sow', '.'));
    }

    /**
     * @test
     */
    public function referenceBackslash()
    {
        $pattern = new Pattern('\w+ (\w+)');
        $this->assertSame(
            'Mr \1',
            $pattern->replace('Aemon Targaryen', 'Mr \1'));
    }

    /**
     * @test
     */
    public function referenceDollar()
    {
        $pattern = new Pattern('\w+ (\w+)');
        $this->assertSame(
            'Mr $1',
            $pattern->replace('Aemon Targaryen', 'Mr $1'));
    }

    /**
     * @test
     */
    public function referenceDollarCurly()
    {
        $pattern = new Pattern('\w+ (\w+)');
        $this->assertSame(
            'Mr ${1}',
            $pattern->replace('Aemon Targaryen', 'Mr ${1}'));
    }
}
