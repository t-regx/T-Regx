<?php
namespace Test\Unit\TRegx\CleanRegex\Internal;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;
use TRegx\CleanRegex\Internal\CompositePatternMapper;

class CompositePatternMapperTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCreate()
    {
        // given
        $mapper = new CompositePatternMapper([
            '[A-Z]+',
            '/[A-Z0-9]+/',
            '/[A-Z+]/i',
            pattern('[A-Za-z]+', 'u'),
        ]);

        // when
        $mapped = $mapper->create();

        // then
        $expected = [
            '/[A-Z]+/',
            '/[A-Z0-9]+/',
            '/[A-Z+]/i',
            '/[A-Za-z]+/u'
        ];
        $this->assertEquals($expected, $mapped);
    }

    /**
     * @test
     */
    public function shouldThrow_onInvalidPattern()
    {
        // given
        $mapper = new CompositePatternMapper([new stdClass()]);

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("CompositePattern accepts only type Pattern or string, but stdClass given");

        // when
        $mapper->create();
    }
}
