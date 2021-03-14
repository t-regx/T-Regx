<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Delimiter;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\ExplicitDelimiterRequiredException;
use TRegx\CleanRegex\Internal\Delimiter\Delimiterer;
use TRegx\CleanRegex\Internal\Delimiter\Strategy\IdentityStrategy;
use TRegx\SafeRegex\preg;

class DelimitererTest extends TestCase
{
    public function patterns(): array
    {
        return [
            ['FooBar'],
            ['Foo#Bar'],
            ['Foo/Bar'],
            ['Foo/#Bar'],
            ['si/e#m%a'],
            ['s~i/e#m%a'],
            ['s~i/e#++m%a'],
            ['s~i/e#++m%a!'],
            ['s~i/e#++m%a!@'],
            ['s~i/e#++m%a!@_'],
            ['s~i/e#++m%a!@_;'],
            ['s~i/e#++m%a!@_;`'],
            ['s~i/e#++m%a!@_;`-'],
            ['s~i/e#++m%a!@_;==`-'],
            ['s~i/e#++m%a!@_;==`-,'],

            // Closable characters should not be treated as delimiters
            ['[foo]'],
            ['{foo}'],
            ['(foo)'],
            ['<foo>'],
        ];
    }

    /**
     * @test
     * @dataProvider patterns
     * @param string $input
     */
    public function shouldDelimiterPattern(string $input)
    {
        // given
        $delimiterer = new Delimiterer(new IdentityStrategy());

        // when
        $pattern = $delimiterer->delimiter("\Q$input\E");

        // then
        $this->assertTrue(preg::match($pattern, $input) === 1, "Failed asserting that $pattern matches $input");
    }

    /**
     * @test
     */
    public function shouldThrowOnNotEnoughDelimiters()
    {
        // given
        $delimiterer = new Delimiterer(new IdentityStrategy());
        $pattern = "s~i/e#++m%a!@*`_-;=,\1";

        $message = "Unfortunately, CleanRegex couldn't find any indistinct delimiter to match your pattern \"$pattern\". " .
            'Please specify the delimiter explicitly, and escape the delimiter character inside your pattern.';

        // then
        $this->expectException(ExplicitDelimiterRequiredException::class);
        $this->expectExceptionMessage($message);

        // when
        $delimiterer->delimiter($pattern);
    }

    /**
     * @test
     */
    public function shouldReturn_forTrailingBackslash(): void
    {
        // given
        $delimiterer = new Delimiterer(new IdentityStrategy());

        // when
        $result = $delimiterer->delimiter("Foo\\\\");

        // then
        $this->assertSame('/Foo\\\\/', $result);
    }
}
