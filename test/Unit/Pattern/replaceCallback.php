<?php
namespace Test\Unit\Pattern;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;
use TRegx\PhpUnit\DataProviders\DataProvider;
use UnexpectedValueException;
use function Test\Fixture\Functions\catching;
use function Test\Fixture\Functions\collect;

class replaceCallback extends TestCase
{
    public function test()
    {
        $pattern = new Pattern('do|not');
        $replaced = $pattern->replaceCallback('We do not sow', fn(): string => '.');
        $this->assertSame('We . . sow', $replaced);
    }

    /**
     * @test
     */
    public function callable()
    {
        $pattern = new Pattern('Ed{2}ard');
        $this->assertSame('EDDARD Stark', $pattern->replaceCallback('Eddard Stark', '\strToUpper'));
    }

    /**
     * @test
     * @dataProvider invalidReturnValues
     */
    public function invalidReturn($value, string $name)
    {
        $pattern = new Pattern('Summer');
        catching(fn() => $pattern->replaceCallback('Summer', fn() => $value))
            ->assertException(UnexpectedValueException::class)
            ->assertMessage("Replacement must be of type string, given: $name.");
    }

    public function invalidReturnValues(): DataProvider
    {
        return DataProvider::tuples(
            [4, 'integer (4)'],
            [3.14, 'double (3.14)'],
            [null, 'null'],
            [[], 'array (0)'],
            [new \stdClass(), 'stdClass']
        );
    }

    /**
     * @test
     */
    public function argument()
    {
        $pattern = new Pattern('not');
        $pattern->replaceCallback('We do not sow', collect($detail, ''));
        $this->assertSame(6, $detail->offset());
    }

    /**
     * @test
     */
    public function argumentUnicodeOffset()
    {
        $pattern = new Pattern('Fury');
        $pattern->replaceCallback('Óurs is the Fury', collect($detail, ''));
        $this->assertSame(12, $detail->offset());
    }
}
