<?php
namespace Test\Unit\TRegx\CleanRegex\Internal;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\ValueType;

/**
 * @covers \TRegx\CleanRegex\Internal\ValueType
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
