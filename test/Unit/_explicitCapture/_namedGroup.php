<?php
namespace Test\Unit\_explicitCapture;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;

class _namedGroup extends TestCase
{
    /**
     * @test
     */
    public function name()
    {
        $pattern = new Pattern('(?<group>)()', 'n');
        $this->assertTrue($pattern->groupExists('group'));
    }

    /**
     * @test
     */
    public function index()
    {
        $pattern = new Pattern('(?<group>)()', 'n');
        $this->assertTrue($pattern->groupExists(1));
    }

    /**
     * @test
     */
    public function unnamed()
    {
        $pattern = new Pattern('(?<group>)()', 'n');
        $this->assertFalse($pattern->groupExists(2));
    }
}
