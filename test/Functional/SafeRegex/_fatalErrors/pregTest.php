<?php
namespace Test\Functional\TRegx\SafeRegex\_fatalErrors;

use PHPUnit\Framework\TestCase;
use Test\DataProviders;
use Test\Utils\Functions;
use TRegx\SafeRegex\Exception\InvalidReturnValueException;
use TRegx\SafeRegex\preg;

class pregTest extends TestCase
{
    /**
     * @test
     * @dataProvider \Test\DataProviders::allPhpTypes
     * @param mixed $input
     */
    public function shouldNotThrowFatalErrors_forAnyPhpType_grep($input)
    {
        // when
        preg::grep('/./', [$input]);

        // then
        $this->assertTrue(true);
    }

    /**
     * @test
     * @dataProvider validReturnTypes
     * @param mixed $input
     */
    public function shouldNotThrowFatalErrors_forAnyPhpType_replace_callback($input)
    {
        // when
        preg::replace_callback('/./', Functions::constant($input), 'word');

        // then
        $this->assertTrue(true);
    }

    /**
     * @test
     * @dataProvider invalidReturnTypes
     * @param mixed $input
     */
    public function shouldNotThrowFatalErrors_butThrowException_replace_callback($input)
    {
        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid preg_replace_callback() callback return type. Expected type that can be cast to string, but object given');

        // when
        preg::replace_callback('/./', Functions::constant($input), 'word');
    }

    /**
     * @test
     * @dataProvider validReturnTypes
     * @param mixed $input
     */
    public function shouldNotThrowFatalErrors_forAnyPhpType_replace_callback_array($input)
    {
        // when
        preg::replace_callback_array([
            '/./' => Functions::constant($input),
            '/a/' => Functions::constant($input),
        ], 'word');

        // then
        $this->assertTrue(true);
    }

    public function validReturnTypes(): array
    {
        return DataProviders::allPhpTypes('stdClass', 'class', 'function');
    }

    public function invalidReturnTypes(): array
    {
        return array_intersect_key(DataProviders::allPhpTypes(), array_flip(['stdClass', 'class', 'function']));
    }

    /**
     * @test
     * @dataProvider invalidReturnTypes
     * @param mixed $input
     */
    public function shouldNotThrowFatalErrors_butThrowException_replace_callback_array($input)
    {
        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid preg_replace_callback_array() callback return type. Expected type that can be cast to string, but object given');

        // when
        preg::replace_callback_array([
            '/./' => Functions::constant('a'),
            '/a/' => Functions::constant($input),
        ], 'word');
    }

    /**
     * @test
     */
    public function shouldThrow_forInvalidCallback_preg_replace_callback_array()
    {
        // given
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid callback passed to preg_replace_callback_array()');

        // when
        preg::replace_callback_array(['/a/' => 4], 'word');
    }
}
