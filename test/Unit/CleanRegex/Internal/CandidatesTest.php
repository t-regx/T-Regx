<?php
namespace Test\Unit\TRegx\CleanRegex\Internal;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Candidates;
use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Delimiter\UndelimiterablePatternException;
use TRegx\CleanRegex\Internal\UnsuitableStringCondition;

/**
 * @covers \TRegx\CleanRegex\Internal\Candidates
 */
class CandidatesTest extends TestCase
{
    /**
     * @test
     * @dataProvider delimiterables
     */
    public function shouldBeDelimiterable(string $delimiterable, string $expectedDelimiter)
    {
        // given
        $candidates = new Candidates(new UnsuitableStringCondition($delimiterable));

        // when
        $delimiter = $candidates->delimiter();

        // then
        $this->assertEquals(new Delimiter($expectedDelimiter), $delimiter);
    }

    public function delimiterables(): array
    {
        return [
            ['foo', '/'],
            ['foo/bar', '#'],
            ['foo/bar#cat', '%'],
            ['foo/bar#cat%', '~'],
            ['s~i/e%#', '+'],
            ['s~i/e#++m%a', '!'],
            ['s~i/e#++m%a!', '@'],
            ['s~i/e#++m%a!@', '_'],
            ['s~i/e#+%!@_', ';'],
            ['s~i/e#+%!@;_', '`'],
            ['s~i/e#+`%!@;_', '-'],
            ['s~i/e-#+`%!@;_', '='],
            ['s~i/e-#+`%=!@;_', ','],
            ['s~i/,e-#+`%=!@;_', "\1"],
        ];
    }

    /**
     * @test
     */
    public function shouldThrowOnNotEnoughDelimiters()
    {
        // given
        $candidates = new Candidates(new UnsuitableStringCondition("s~i/e#++m%a!@*`_-;=,\1"));

        // then
        $this->expectException(UndelimiterablePatternException::class);

        // when
        $candidates->delimiter();
    }
}
