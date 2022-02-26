<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Delimiter;

use PHPUnit\Framework\TestCase;
use Test\Utils\TestCasePasses;
use TRegx\CleanRegex\Exception\MalformedPcreTemplateException;
use TRegx\CleanRegex\Internal\Delimiter\PcreDelimiter;

/**
 * @covers \TRegx\CleanRegex\Internal\Delimiter\PcreDelimiter
 */
class PcreDelimiterTest extends TestCase
{
    use TestCasePasses;

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
            if (\in_array($byte, $this->legalDelimiterBytes())) {
                $this->shouldBeValidDelimiter(\chr($byte));
            } else {
                $this->shouldBeInvalidDelimiter(\chr($byte));
            }
        }
    }

    private function shouldBeValidDelimiter(string $delimiter): void
    {
        // when
        new PcreDelimiter($delimiter);

        // then
        $this->pass();
    }

    private function shouldBeInvalidDelimiter(string $delimiter): void
    {
        // then
        $this->expectException(MalformedPcreTemplateException::class);
        $this->expectExceptionMessage("PCRE-compatible template is malformed, starting with an unexpected delimiter '$delimiter'");

        // when
        new PcreDelimiter($delimiter);
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
