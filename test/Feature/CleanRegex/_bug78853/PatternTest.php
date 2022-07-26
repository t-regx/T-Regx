<?php
namespace Test\Feature\CleanRegex\_bug78853;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;

class PatternTest extends TestCase
{
    /**
     * @test
     * @link https://bugs.php.net/bug.php?id=78853
     */
    public function shouldTest()
    {
        // when, then
        $this->assertTrue(Pattern::of('^|\d{1,2}$')->test('7'));
    }

    /**
     * @test
     * @link https://bugs.php.net/bug.php?id=78853
     */
    public function shouldFail()
    {
        // when, then
        $this->assertFalse(Pattern::of('^|\d{1,2}$')->fails('7'));
    }

    /**
     * @test
     * @link https://bugs.php.net/bug.php?id=78853
     */
    public function shouldCount()
    {
        // when, then
        $this->assertSame(2, Pattern::of('^|\d{1,2}$')->count('7'));
    }
}
