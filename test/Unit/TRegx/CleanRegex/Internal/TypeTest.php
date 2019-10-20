<?php
namespace Test\Unit\TRegx\CleanRegex\Internal;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Type;

class TypeTest extends TestCase
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
        $string = Type::asString($value);

        // then
        $this->assertEquals($expectedString, $string);
    }
}
