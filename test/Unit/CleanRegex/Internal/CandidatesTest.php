<?php
namespace Test\Unit\CleanRegex\Internal;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Candidates;
use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Delimiter\UndelimitablePatternException;
use TRegx\CleanRegex\Internal\UnsuitableStringCondition;

/**
 * @covers \TRegx\CleanRegex\Internal\Candidates
 */
class CandidatesTest extends TestCase
{
    /**
     * @test
     * @dataProvider delimitables
     */
    public function shouldBeDelimitable(string $delimitable, string $expectedDelimiter)
    {
        // given
        $candidates = new Candidates(new UnsuitableStringCondition($delimitable));

        // when
        $delimiter = $candidates->delimiter();

        // then
        $this->assertEquals(new Delimiter($expectedDelimiter), $delimiter);
    }

    public function delimitables(): array
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
        $this->expectException(UndelimitablePatternException::class);

        // when
        $candidates->delimiter();
    }
}
