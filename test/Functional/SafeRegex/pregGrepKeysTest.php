<?php
namespace Test\Functional\TRegx\SafeRegex;

use PHPUnit\Framework\TestCase;
use Test\Utils\PhpVersionDependent;
use TRegx\SafeRegex\Exception\MalformedPatternException;
use TRegx\SafeRegex\preg;

class pregGrepKeysTest extends TestCase
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
        $this->assertEquals(['9' => ['Foo', 'Bar'], '10' => true, '11' => true], $result);
    }

    /**
     * @test
     */
    public function shouldGrepKeys_inverted()
    {
        // given
        $input = [
            '9'  => ['Foo', 'Bar'],
            'a'  => false,
            '10' => true,
            'b'  => new \stdClass(),
            '11' => true
        ];

        // when
        $result = preg::grep_keys('/\d+/', $input, PREG_GREP_INVERT);

        // then
        $this->assertEquals(['a' => false, 'b' => new \stdClass()], $result);
    }

    /**
     * @test
     */
    public function shouldGetEmptyArray_emptyInput_preg_grep_keys()
    {
        // when
        $result = preg::grep_keys('//', []);

        // then
        $this->assertEquals([], $result);
    }

    /**
     * @test
     */
    public function shouldThrow_forInvalidPattern()
    {
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage(PhpVersionDependent::getAsymmetricQuantifierMessage(0));

        // when
        preg::grep_keys('/+/', []);
    }
}
