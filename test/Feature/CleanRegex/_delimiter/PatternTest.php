<?php
namespace Test\Feature\TRegx\CleanRegex\_delimiter;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;
use TRegx\SafeRegex\preg;

class PatternTest extends TestCase
{
    /**
     * @test
     * @dataProvider patterns
     * @param string $input
     */
    public function test(string $input)
    {
        // when
        $pattern = Pattern::of("\Q$input\E");

        // then
        $this->assertTrue(preg::match($pattern, $input) === 1, "Failed asserting that $pattern matches $input");
    }

    public function patterns(): array
    {
        return [
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
}
