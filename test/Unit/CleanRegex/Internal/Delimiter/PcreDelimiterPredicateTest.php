<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Delimiter;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Delimiter\PcreDelimiterPredicate;

/**
 * @covers \TRegx\CleanRegex\Internal\Delimiter\PcreDelimiterPredicate
 */
class PcreDelimiterPredicateTest extends TestCase
{
    /**
     * @test
     */
    public function test()
    {
        /*
         * I use foreach instead of PhpUnit @dataProvider, because with
         * data provider the test runs in about 50ms, and foreach runs in
         * about 1ms. 50ms is too much for this trivial test.
         */

        foreach (\range(0, 255) as $byte) {
            $this->shouldBeValidDelimiter(\chr($byte), \in_array($byte, $this->legalDelimiterBytes()));
        }
    }

    /**
     * @param string $delimiter
     * @param bool $expected
     */
    private function shouldBeValidDelimiter(string $delimiter, bool $expected): void
    {
        // given
        $predicate = new PcreDelimiterPredicate();

        // when
        $test = $predicate->test($delimiter);

        // then
        $this->assertSame($expected, $test);
    }

    public function legalDelimiterBytes(): array
    {
        return [
            1, 2, 3, 4, 5, 6, 7, 8,
            14, 15, 16, 17, 18, 19,
            20, 21, 22, 23, 24, 25, 26, 27, 28, 29,
            30, 31, 33, 34, 35, 36, 37, 38, 39,
            41, 42, 43, 44, 45, 46, 47,
            58, 59,
            61, 62, 63, 64,
            93, 94, 95, 96,
            124, 125, 126, 127
        ];
    }
}
