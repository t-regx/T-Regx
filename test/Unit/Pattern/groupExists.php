<?php
namespace Test\Unit\Pattern;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Regex\Pattern;
use function Test\Fixture\Functions\catching;

class groupExists extends TestCase
{
    /**
     * @test
     */
    public function wholeMatch()
    {
        $pattern = new Pattern('(Valar) (?<morghulis>)');
        $this->assertTrue($pattern->groupExists(0));
    }

    /**
     * @test
     */
    public function groupExists()
    {
        $pattern = new Pattern('(Valar)');
        $this->assertTrue($pattern->groupExists(1));
    }

    /**
     * @test
     */
    public function groupMissing()
    {
        $pattern = new Pattern('(Valar)');
        $this->assertFalse($pattern->groupExists(2));
    }

    /**
     * @test
     */
    public function groupExistsByName()
    {
        $pattern = new Pattern('(?<group>Valar)');
        $this->assertTrue($pattern->groupExists('group'));
    }

    /**
     * @test
     */
    public function groupMissingByName()
    {
        $pattern = new Pattern('(?<group>Valar)');
        $this->assertFalse($pattern->groupExists('other'));
    }

    /**
     * @test
     */
    public function invalidName()
    {
        $pattern = new Pattern('pattern');
        catching(fn() => $pattern->groupExists('2group'))
            ->assertException(InvalidArgumentException::class)
            ->assertMessage("Group name must be an alphanumeric string, not starting with a digit, given: '2group'.");
    }

    /**
     * @test
     */
    public function negativeIndex()
    {
        $pattern = new Pattern('(Valar)');
        catching(fn() => $pattern->groupExists(-2))
            ->assertException(\InvalidArgumentException::class)
            ->assertMessage("Group index must be a non-negative integer, given: -2.");
    }

    /**
     * @test
     */
    public function nonPrintable()
    {
        $pattern = new Pattern('any');
        catching(fn() => $pattern->groupExists("\w\0\r"))
            ->assertException(\InvalidArgumentException::class)
            ->assertMessage("Group name must be an alphanumeric string, not starting with a digit, given: '\w  '.");
    }

    /**
     * @test
     */
    public function invalidType()
    {
        $pattern = new Pattern('pattern');
        catching(fn() => $pattern->groupExists(3.14))
            ->assertException(\InvalidArgumentException::class)
            ->assertMessage('Group key must be an integer or a string, given: double (3.14).');
    }
}
