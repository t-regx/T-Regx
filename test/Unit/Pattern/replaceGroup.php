<?php
namespace Test\Unit\Pattern;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Regex\GroupException;
use Regex\Pattern;
use TRegx\PhpUnit\DataProviders\DataProvider;
use function Test\Fixture\Functions\catching;

class replaceGroup extends TestCase
{
    /**
     * @test
     */
    public function name()
    {
        $pattern = new Pattern('(?<firstName>\w+) (?<lastName>\w+)$');
        $replaced = $pattern->replaceGroup('Warden of the north: Eddard Stark', 'lastName');
        $this->assertSame('Warden of the north: Stark', $replaced);
    }

    /**
     * @test
     */
    public function index()
    {
        $pattern = new Pattern('(\w+) (\w+)$');
        $replaced = $pattern->replaceGroup('Warden of the north: Eddard Stark', 2);
        $this->assertSame('Warden of the north: Stark', $replaced);
    }

    /**
     * @test
     */
    public function invalidName()
    {
        $pattern = new Pattern('any');
        catching(fn() => $pattern->replaceGroup('subject', '2group'))
            ->assertException(InvalidArgumentException::class)
            ->assertMessage("Group name must be an alphanumeric string, not starting with a digit, given: '2group'.");
    }

    /**
     * @test
     */
    public function lastOptional()
    {
        $pattern = new Pattern('(Valar) (?<optional>dohaeris)?');
        catching(fn() => $pattern->replaceGroup('Valar Morghulis', 'optional'))
            ->assertException(GroupException::class)
            ->assertMessage("Capturing group is not matched: 'optional'.");
    }

    /**
     * @test
     */
    public function empty()
    {
        $pattern = new Pattern('Valar (?<group>)');
        $this->assertSame('"dohaeris"', $pattern->replaceGroup('"Valar dohaeris"', 1));
    }

    /**
     * @test
     * @dataProvider groups
     */
    public function unmatched($nameOrIndex, string $name)
    {
        $pattern = new Pattern('Valar (?<group>morghulis)?()');
        catching(fn() => $pattern->replaceGroup('Valar dohaeris', $nameOrIndex))
            ->assertException(GroupException::class)
            ->assertMessage("Capturing group is not matched: $name.");
    }

    /**
     * @test
     * @dataProvider groups
     */
    public function missing($nameOrIndex, string $expectedName)
    {
        $pattern = new Pattern('Winter is coming!');
        catching(fn() => $pattern->replaceGroup('Winter is coming!', $nameOrIndex))
            ->assertException(GroupException::class)
            ->assertMessage("Capturing group does not exist: $expectedName.");
    }

    public function groups(): DataProvider
    {
        return DataProvider::tuples(
            ['group', "'group'"],
            [1, '#1']);
    }
}
