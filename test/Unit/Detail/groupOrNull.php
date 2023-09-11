<?php
namespace Test\Unit\Detail;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Regex\Detail;
use Regex\GroupException;
use Regex\Pattern;
use TRegx\PhpUnit\DataProviders\DataProvider;
use function Test\Fixture\Functions\catching;

class groupOrNull extends TestCase
{
    /**
     * @test
     */
    public function name()
    {
        $detail = $this->detail('\w+ (?<house>\w+)', 'Eddard Stark');
        $this->assertSame('Stark', $detail->groupOrNull('house'));
    }

    /**
     * @test
     */
    public function index()
    {
        $detail = $this->detail('\w+ (?<house>\w+)', 'Eddard Stark');
        $this->assertSame('Stark', $detail->groupOrNull(1));
    }

    /**
     * @test
     */
    public function invalidName()
    {
        $detail = $this->anyDetail();
        catching(fn() => $detail->groupOrNull('2group'))
            ->assertException(InvalidArgumentException::class)
            ->assertMessage("Group name must be an alphanumeric string, not starting with a digit, given: '2group'.");
    }

    /**
     * @test
     */
    public function lastOptional()
    {
        // given
        $detail = $this->detail('(Valar) (?<optional>dohaeris)?', 'Valar Morghulis');
        // when
        $this->assertNull($detail->groupOrNull('optional'));
    }

    /**
     * @test
     */
    public function empty()
    {
        $detail = $this->detail('(?<empty>)', 'We do not sow');
        $this->assertSame('', $detail->groupOrNull('empty'));
    }

    /**
     * @test
     * @dataProvider groups
     */
    public function missingGroups($nameOrIndex, string $name)
    {
        $detail = $this->anyDetail();
        catching(fn() => $detail->groupOrNull($nameOrIndex))
            ->assertException(GroupException::class)
            ->assertMessage("Capturing group does not exist: $name.");
    }

    /**
     * @test
     * @dataProvider groups
     */
    public function unmatched($nameOrIndex, string $name)
    {
        // given
        $detail = $this->detail('(?<name>Eddard)? (Stark)', ' Stark');
        // when
        $this->assertNull($detail->groupOrNull($nameOrIndex));
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
