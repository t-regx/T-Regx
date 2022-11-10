<?php
namespace Test\Unit\CleanRegex\Internal\Delimiter;

use PHPUnit\Framework\TestCase;
use Test\Utils\TestCase\TestCasePasses;
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
     * @dataProvider bytes
     */
    public function test(int $byte)
    {
        if ($this->isLegalDelimiter($byte)) {
            $this->shouldBeValidDelimiter(\chr($byte));
        } else {
            $this->shouldBeInvalidDelimiter(\chr($byte));
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
        $this->expectExceptionMessage($this->expectedMalformedPatternMessage($delimiter));
        // when
        new PcreDelimiter($delimiter);
    }

    public function bytes(): array
    {
        return provided(\range(0, 255));
    }

    private function expectedMalformedPatternMessage(string $delimiter): string
    {
        if (\ctype_alnum($delimiter)) {
            return "PCRE-compatible template is malformed, alphanumeric delimiter '$delimiter'";
        }
        return "PCRE-compatible template is malformed, starting with an unexpected delimiter '$delimiter'";
    }

    private function isLegalDelimiter(int $byte): bool
    {
        return \in_array($byte, $this->legalDelimiterBytes());
    }

    private function legalDelimiterBytes(): array
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
