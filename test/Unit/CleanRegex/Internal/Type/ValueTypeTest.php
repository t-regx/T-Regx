<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Type;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Type\ValueType;

/**
 * @covers \TRegx\CleanRegex\Internal\Type\ValueType
 */
class ValueTypeTest extends TestCase
{
    /**
     * @test
     * @dataProvider \Test\DataProviders::allPhpTypes()
     * @param mixed $value
     * @param string $expectedString
     */
    public function shouldGetMessageWithType($value, string $expectedString)
    {
        // when
        $type = new ValueType($value);

        // then
        $this->assertSame($expectedString, "$type");
    }
}
