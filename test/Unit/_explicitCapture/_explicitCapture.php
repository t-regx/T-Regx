<?php
namespace Test\Unit\_explicitCapture;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;

class _explicitCapture extends TestCase
{
    /**
     * @test
     */
    public function disabled()
    {
        $pattern = new Pattern('(?-n)()', 'n');
        $this->assertTrue($pattern->groupExists(1));
    }

    /**
     * @test
     */
    public function unicode()
    {
        $this->assertMatch(new Pattern('(*UTF)(.)', 'n'), '€');
    }

    /**
     * @test
     */
    public function byte()
    {
        $byte = new Pattern('.', 'n');
        $this->assertSame(chr(226), (string)$byte->first('€'));
    }

    /**
     * @test
     */
    public function notEmpty()
    {
        $unicodeCharacter = new Pattern('(*UTF)(*NOTEMPTY)', 'un');
        $this->assertFalse($unicodeCharacter->test(''));
    }

    /**
     * @test
     */
    public function optionSetting()
    {
        $character = new Pattern('(*UTF)(.)', 'n');
        $this->assertFalse($character->groupExists(1));
    }

    /**
     * @test
     */
    public function commentsAndWhitespace()
    {
        $this->assertMatch(new Pattern('(*UTF) word #comment', 'xn'), 'word');
    }

    private function assertMatch(Pattern $pattern, string $subject)
    {
        $this->assertSame($subject, (string)$pattern->first($subject));
    }
}
