<?php
namespace Test\Unit\Detail;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Regex\Detail;
use Regex\GroupException;
use Regex\Pattern;
use TRegx\PhpUnit\DataProviders\DataProvider;
use function Test\Fixture\Functions\catching;

class group extends TestCase
{
    /**
     * @test
     */
    public function name()
    {
        $detail = $this->detail('\w+ (?<house>\w+)', 'Eddard Stark');
        $this->assertSame('Stark', $detail->group('house'));
    }

    /**
     * @test
     */
    public function index()
    {
        $detail = $this->detail('\w+ (?<house>\w+)', 'Eddard Stark');
        $this->assertSame('Stark', $detail->group(1));
    }

    /**
     * @test
     */
    public function invalidName()
    {
        $detail = $this->anyDetail();
        catching(fn() => $detail->group('2group'))
            ->assertException(InvalidArgumentException::class)
            ->assertMessage("Group name must be an alphanumeric string, not starting with a digit, given: '2group'.");
    }

    /**
     * @test
     */
    public function lastOptional()
    {
        $detail = $this->detail('(Valar) (?<optional>dohaeris)?', 'Valar Morghulis');
        catching(fn() => $detail->group('optional'))
            ->assertException(GroupException::class)
            ->assertMessage("Capturing group is not matched: 'optional'.");
    }

    /**
     * @test
     */
    public function empty()
    {
        $detail = $this->detail('(?<empty>)', 'We do not sow');
        $this->assertSame('', $detail->group('empty'));
    }

    /**
     * @test
     * @dataProvider groups
     */
    public function missingGroups($nameOrIndex, string $name)
    {
        $detail = $this->anyDetail();
        catching(fn() => $detail->group($nameOrIndex))
            ->assertException(GroupException::class)
            ->assertMessage("Capturing group does not exist: $name.");
    }

    /**
     * @test
     * @dataProvider groups
     */
    public function unmatched($nameOrIndex, string $name)
    {
        $detail = $this->detail('(?<name>Eddard)? (Stark)', ' Stark');
        catching(fn() => $detail->group($nameOrIndex))
            ->assertException(GroupException::class)
            ->assertMessage("Capturing group is not matched: $name.");
    }

    public function groups(): DataProvider
    {
        return DataProvider::tuples(
            ['name', "'name'"],
            [1, '#1']);
    }

    private function anyDetail(): Detail
    {
        return $this->detail('any', 'any');
    }

    private function detail(string $pattern, string $subject): Detail
    {
        return (new Pattern($pattern))->first($subject);
    }
}
