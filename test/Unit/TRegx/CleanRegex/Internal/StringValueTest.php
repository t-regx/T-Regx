<?php
namespace Test\Unit\TRegx\CleanRegex\Internal;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\CleanRegex\Internal\StringValue;

class StringValueTest extends TestCase
{
    /**
     * @test
     * @dataProvider objectsAndMessages
     * @param mixed  $value
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
                'null'
            ],
            [
                true,
                'boolean (true)'
            ],
            [
                false,
                'boolean (false)'
            ],
            [
                2,
                'integer (2)'
            ],
            [
                2.23,
                'double (2.23)'
            ],
            [
                "She's sexy",
                "string ('She\'s sexy')"
            ],
            [
                [1, 2, 3],
                'array (3)'
            ],
            [
                $this->getResource(),
                'resource'
            ],
            [
                new \stdClass(),
                'stdClass'
            ],
            [
                new Pattern(''),
                Pattern::class
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
