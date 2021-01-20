<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Quotable\Factory;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Factory\AlterationFactory;

class AlterationFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCreateQuotableString()
    {
        // given
        $factory = new AlterationFactory('');

        // when
        $quoteable = $factory->quotable('5% you (are|is) welcome');

        // then
        $this->assertSame('5\%\ you\ \(are\|is\)\ welcome', $quoteable->quote('%'));
    }

    /**
     * @test
     */
    public function shouldQuoteArray()
    {
        // given
        $factory = new AlterationFactory('');

        // when
        $quoteable = $factory->quotable(['first 1%', 'second 2%']);

        // then
        $this->assertSame('(?:first\ 1\%|second\ 2\%)', $quoteable->quote('%'));
    }

    /**
     * @test
     * @dataProvider arrayDuplicatesByFlags
     * @param string $flags
     * @param string $expected
     */
    public function shouldRemoveDuplicates(string $flags, string $expected)
    {
        // given
        $factory = new AlterationFactory($flags);

        // when
        $quoteable = $factory->quotable(['FOO', 'foo', 'PIęć', 'pięć', 'Żółć', 'ŻÓŁĆ']);

        // then
        $this->assertSame($expected, $quoteable->quote('%'), "Failed to assert that duplicates were removed with flags '$flags'.");
    }

    public function arrayDuplicatesByFlags(): array
    {
        return [
            ['UI', '(?:FOO|foo|PIęć|pięć|Żółć|ŻÓŁĆ)'],
            ['mu', '(?:FOO|foo|PIęć|pięć|Żółć|ŻÓŁĆ)'],
            ['im', '(?:FOO|PIęć|Żółć|ŻÓŁĆ)'],
            ['uim', '(?:FOO|PIęć|Żółć)'],
        ];
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidType()
    {
        // given
        $factory = new AlterationFactory('');

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid bound value. Expected string, but integer (4) given");

        // when
        $factory->quotable(4);
    }
}
