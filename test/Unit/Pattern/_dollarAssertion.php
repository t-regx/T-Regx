<?php
namespace Test\Unit\Pattern;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;

class _dollarAssertion extends TestCase
{
    /**
     * @test
     */
    public function test()
    {
        $pattern = new Pattern('^one$');
        $this->assertFalse($pattern->test("one\n"));
    }

    /**
     * @test
     */
    public function trailingNewline()
    {
        $pattern = new Pattern('^one\Z');
        $this->assertTrue($pattern->test("one\n"));
    }

    /**
     * @test
     */
    public function trailingNewlineEndOfSubject()
    {
        $pattern = new Pattern('^one\z');
        $this->assertFalse($pattern->test("one\n"));
    }
}
