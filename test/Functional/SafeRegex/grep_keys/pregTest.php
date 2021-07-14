<?php
namespace Test\Functional\TRegx\SafeRegex\grep_keys;

use PHPUnit\Framework\TestCase;
use TRegx\Exception\MalformedPatternException;
use TRegx\SafeRegex\preg;

/**
 * @coversNothing
 */
class pregTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGrepKeys()
    {
        // given
        $input = [
            '9'  => ['Foo', 'Bar'],
            'a'  => false,
            '10' => true,
            'b'  => new \stdClass(),
            '11' => true,
        ];

        // when
        $result = preg::grep_keys('/\d+/', $input);

        // then
        $this->assertSame(['9' => ['Foo', 'Bar'], '10' => true, '11' => true], $result);
    }

    /**
     * @test
     */
    public function shouldGrepKeys_inverted()
    {
        // given
        $object = new \stdClass();
        $input = [
            '9'  => ['Foo', 'Bar'],
            'a'  => false,
            '10' => true,
            'b'  => $object,
            '11' => true
        ];

        // when
        $result = preg::grep_keys('/\d+/', $input, PREG_GREP_INVERT);

        // then
        $this->assertSame(['a' => false, 'b' => $object], $result);
    }

    /**
     * @test
     */
    public function shouldGetEmptyArray_emptyInput_preg_grep_keys()
    {
        // when
        $result = preg::grep_keys('//', []);

        // then
        $this->assertEmpty($result);
    }

    /**
     * @test
     */
    public function shouldThrow_forInvalidPattern()
    {
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage("Quantifier does not follow a repeatable item at offset 0");

        // when
        preg::grep_keys('/+/', []);
    }
}
