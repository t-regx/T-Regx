<?php
namespace Test\Feature\TRegx\CleanRegex\_delimiter;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsPattern;
use TRegx\CleanRegex\Pattern;

/**
 * @coversNothing
 */
class PatternTest extends TestCase
{
    use AssertsPattern;

    /**
     * @test
     * @dataProvider strings
     * @param string $input
     */
    public function test(string $input)
    {
        // when
        $pattern = Pattern::of("\Q$input\E");

        // then
        $this->assertConsumesFirst($input, $pattern);
    }

    public function strings(): array
    {
        return [
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
        ];
    }
}
