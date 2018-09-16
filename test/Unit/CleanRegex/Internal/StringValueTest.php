<?php
namespace Test\Unit\CleanRegex\Internal;

use CleanRegex\Internal\Pattern;
use CleanRegex\Internal\StringValue;
use PHPUnit\Framework\TestCase;

class StringValueTest extends TestCase
{
    /**
     * @test
     * @dataProvider objectsAndMessages
     * @param mixed $value
     * @param string $expectedString
     */
    public function shouldGetMessageWithType($value, string $expectedString)
    {
        // given
        $stringValue = new StringValue($value);

        // when
        $string = $stringValue->getString();

        // then
        $this->assertEquals($expectedString, $string);
    }

    public function objectsAndMessages()
    {
        return [
            [
                null,
                '(null)'
            ],
            [
                true,
                '(boolean) true'
            ],
            [
                false,
                '(boolean) false'
            ],
            [
                2,
                '(integer) 2'
            ],
            [
                2.23,
                '(double) 2.23'
            ],
            [
                "She's sexy",
                "(string) 'She\'s sexy'"
            ],
            [
                [1, 2, 3],
                '(array)'
            ],
            [
                $this->getResource(),
                '(resource)'
            ],
            [
                new \stdClass(),
                'stdClass'
            ],
            [
                new Pattern(''),
                'CleanRegex\Internal\Pattern'
            ],
            [
                function () {
                },
                'Closure'
            ]
        ];
    }

    private function getResource()
    {
        $resources = get_resources();
        return reset($resources);
    }
}
