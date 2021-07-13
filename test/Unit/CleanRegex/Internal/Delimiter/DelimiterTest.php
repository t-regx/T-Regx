<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Delimiter;

use PHPUnit\Framework\TestCase;
use Test\Utils\Impl\VerifyQuotable;
use TRegx\CleanRegex\Exception\ExplicitDelimiterRequiredException;
use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Quotable\UserInputQuotable;

/**
 * @covers \TRegx\CleanRegex\Internal\Delimiter\Delimiter
 */
class DelimiterTest extends TestCase
{
    /**
     * @test
     */
    public function shouldDelimit()
    {
        // given
        $delimiter = new Delimiter('/');

        // when
        $delimited = $delimiter->delimited(new UserInputQuotable('foo/bar'), new Flags(''));

        // then
        $this->assertSame('/foo\/bar/', $delimited);
    }

    /**
     * @test
     */
    public function shouldDelimitWithFlags()
    {
        // given
        $delimiter = new Delimiter('#');

        // when
        $pattern = $delimiter->delimited(new UserInputQuotable('foo/bar#cat'), new Flags('i'));

        // then
        $this->assertSame('#foo/bar\#cat#i', $pattern);
    }

    /**
     * @test
     * @dataProvider delimiterables
     */
    public function shouldBeDelimiterable(string $delimiterable, string $expectedDelimiter)
    {
        // given
        $delimiter = Delimiter::suitable($delimiterable);

        // when
        $pattern = $delimiter->delimited(new VerifyQuotable('X', $expectedDelimiter), new Flags(''));

        // then
        $this->assertSame("{$expectedDelimiter}X{$expectedDelimiter}", $pattern);
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
        $message = "Unfortunately, CleanRegex couldn't find any indistinct delimiter to " .
            "match your pattern \"s~i/e#++m%a!@*`_-;=,\1\". Please specify the delimiter " .
            "explicitly, and escape the delimiter character inside your pattern.";

        // then
        $this->expectException(ExplicitDelimiterRequiredException::class);
        $this->expectExceptionMessage($message);

        // when
        Delimiter::suitable("s~i/e#++m%a!@*`_-;=,\1");
    }
}
