<?php
namespace Test\Functional\TRegx\SafeRegex;

use PHPUnit\Framework\TestCase;
use TRegx\SafeRegex\Exception\CompileSafeRegexException;
use TRegx\SafeRegex\preg;

class pregTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetEmptyArray_emptyInput_preg_replace()
    {
        // when
        $result = preg::replace([], [], []);

        // then
        $this->assertEquals([], $result);
    }

    /**
     * @test
     */
    public function shouldGetEmptyArray_emptyInput_preg_filter()
    {
        // when
        $result = preg::filter([], [], []);

        // then
        $this->assertEquals([], $result);
    }

    /**
     * @test
     */
    public function shouldGetEmptyArray_arrayInput_filteredOut()
    {
        // when
        $result = preg::filter('/c/', '', ['a', 'b']);

        // then
        $this->assertEquals([], $result);
    }

    /**
     * @test
     */
    public function shouldBeIndependentOfCallback_preg_replace_callback()
    {
        // then
        $this->expectException(CompileSafeRegexException::class);
        $this->expectExceptionMessage("No ending delimiter '/' found");

        // when
        try {
            preg::replace_callback('/valid/', function () {
                preg_replace_callback('/invalid', 'strtoupper', '');
                return 'maybe';
            }, 'valid');
        } catch (CompileSafeRegexException $exception) {
            $this->assertEquals($exception->getMessage(), "No ending delimiter '/' found");
            $this->assertEquals($exception->getInvokingMethod(), 'preg_replace_callback');

            throw $exception;
        }
    }
}
