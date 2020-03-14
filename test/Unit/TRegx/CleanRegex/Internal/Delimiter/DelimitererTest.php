<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Delimiter;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\ExplicitDelimiterRequiredException;
use TRegx\CleanRegex\Internal\Delimiter\Delimiterer;
use TRegx\CleanRegex\Internal\Delimiter\Strategy\IdentityStrategy;
use TRegx\SafeRegex\preg;

class DelimitererTest extends TestCase
{
    public function patternsAndResults()
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
     * @dataProvider patternsAndResults
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

        // then
        $this->expectException(ExplicitDelimiterRequiredException::class);

        // when
        $delimiterer->delimiter('s~i/e#++m%a!@*`_-;=,' . chr(1));
    }
}
