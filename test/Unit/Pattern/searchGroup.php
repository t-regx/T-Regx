<?php
namespace Test\Unit\Pattern;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Regex\GroupException;
use Regex\Pattern;
use function Test\Fixture\Functions\catching;

class searchGroup extends TestCase
{
    public function test()
    {
        $pattern = new Pattern('[ab]([12])');
        $this->assertSame(
            ['1', '2'],
            $pattern->searchGroup('a1, b2', 1));
    }

    /**
     * @test
     */
    public function unmatchedGroup()
    {
        $pattern = new Pattern('Valar (morghulis)?');
        $this->assertSame([null], $pattern->searchGroup('Valar ', 1));
    }

    /**
     * @test
     */
    public function unmatchedSubject()
    {
        $pattern = new Pattern('Valar (morghulis)');
        $this->assertEmpty($pattern->searchGroup('Dohaeris', 1));
    }

    /**
     * @test
     */
    public function missingGroup()
    {
        $pattern = new Pattern('pattern');
        catching(fn() => $pattern->searchGroup('subject', 'missing'))
            ->assertException(GroupException::class)
            ->assertMessage("Capturing group does not exist: 'missing'.");
    }

    /**
     * @test
     */
    public function invalidName()
    {
        $pattern = new Pattern('pattern');
        catching(fn() => $pattern->searchGroup('subject', '2group'))
            ->assertException(InvalidArgumentException::class)
            ->assertMessage("Group name must be an alphanumeric string, not starting with a digit, given: '2group'.");
    }

    /**
     * @test
     */
    public function lastEmpty()
    {
        $pattern = new Pattern('Valar ((?:morghulis)?)');
        $this->assertSame([''], $pattern->searchGroup('Valar ', 1));
    }
}
