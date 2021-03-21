<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Delimiter;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\ExplicitDelimiterRequiredException;
use TRegx\CleanRegex\Internal\Delimiter\DelimiterFinder;

class DelimiterFinderTest extends TestCase
{
    /**
     * @test
     */
    public function shouldDelimiterPattern()
    {
        // given
        $delimiterer = new DelimiterFinder();

        // when
        $pattern = $delimiterer->chooseDelimiter("s~i/e#++m%a!@");

        // then
        $this->assertSame('_', $pattern);
    }

    /**
     * @test
     */
    public function shouldThrowOnNotEnoughDelimiters()
    {
        // given
        $delimiterer = new DelimiterFinder();
        $pattern = "s~i/e#++m%a!@*`_-;=,\1";

        $message = "Unfortunately, CleanRegex couldn't find any indistinct delimiter to match your pattern \"$pattern\". " .
            'Please specify the delimiter explicitly, and escape the delimiter character inside your pattern.';

        // then
        $this->expectException(ExplicitDelimiterRequiredException::class);
        $this->expectExceptionMessage($message);

        // when
        $delimiterer->chooseDelimiter($pattern);
    }

    /**
     * @test
     */
    public function shouldReturn_forTrailingBackslash(): void
    {
        // given
        $delimiterer = new DelimiterFinder();

        // when
        $result = $delimiterer->chooseDelimiter("/#\\\\");

        // then
        $this->assertSame('%', $result);
    }
}
