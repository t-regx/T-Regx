<?php
namespace Test\Feature\CleanRegex\_prepared\_alteration\_empty;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsPattern;
use TRegx\CleanRegex\Pattern;

class TemplateTest extends TestCase
{
    use AssertsPattern;

    public function alterations(): array
    {
        return [
            '::template()' => [function (string $pattern): Pattern {
                return Pattern::template($pattern)->alteration([]);
            }],
            '::builder()'  => [function (string $pattern): Pattern {
                return Pattern::builder($pattern)->alteration([])->build();
            }],
        ];
    }

    /**
     * @test
     * @dataProvider alterations
     */
    public function shouldFailMatchingEmptyAlteration(callable $alteration)
    {
        // when
        $pattern = $alteration('Value:@');
        // then
        $this->assertPatternFails($pattern, 'Value:');
        $this->assertPatternIs('/Value:(*FAIL)/', $pattern);
    }

    /**
     * @test
     * @dataProvider alterations
     */
    public function shouldFailMatchingEmptyAlterationUnconjugated(callable $alteration)
    {
        // when
        $pattern = $alteration('Value:@:');
        // then
        $this->assertPatternFails($pattern, 'Value::');
        $this->assertPatternIs('/Value:(?>(*FAIL)):/', $pattern);
    }

    /**
     * @test
     * @dataProvider alterations
     */
    public function shouldFailMatchingEmptyAlterationNested(callable $alteration)
    {
        // when
        $pattern = $alteration('(Value:@)');
        // then
        $this->assertPatternFails($pattern, 'Value::');
        $this->assertPatternIs('/(Value:(?>(*FAIL)))/', $pattern);
    }

    /**
     * @test
     * @dataProvider alterations
     */
    public function shouldFailMatchingEmptyAlterationOptional(callable $alteration)
    {
        // when
        $pattern = $alteration('Value:@?');
        // then
        $this->assertConsumesFirst('Value:', $pattern);
        $this->assertPatternIs('/Value:(?>(*FAIL))?/', $pattern);
    }
}