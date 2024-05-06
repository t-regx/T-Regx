<?php
namespace Test\Unit\_groupIdentifier;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;
use TRegx\PhpUnit\DataProviders\DataProvider;
use function Test\Fixture\Functions\catching;

class _type extends TestCase
{
    /**
     * @dataProvider invalidTypes
     */
    public function test($value, string $name)
    {
        $pattern = new Pattern('(Valar)');
        catching(fn() => $pattern->groupExists($value))
            ->assertException(\InvalidArgumentException::class)
            ->assertMessage("Group key must be an integer or a string, given: $name.");
    }

    public function invalidTypes(): DataProvider
    {
        return DataProvider::tuples(
            [0.1, 'double (0.1)'],
            [1000.0, 'double (1000)'],
            [null, 'null'],
            [[], 'array (0)'],
            [$this->resource(), 'resource'],
            [new \stdClass(), 'stdClass']);
    }

    private function resource()
    {
        $resources = \get_resources();
        return \current($resources);
    }
}
