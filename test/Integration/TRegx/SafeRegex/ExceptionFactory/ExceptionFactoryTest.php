<?php
namespace Test\Integration\TRegx\SafeRegex\ExceptionFactory;

use PHPUnit\Framework\TestCase;
use TRegx\SafeRegex\Errors\ErrorsCleaner;
use TRegx\SafeRegex\Exception\CompileSafeRegexException;
use TRegx\SafeRegex\Exception\RuntimeSafeRegexException;
use TRegx\SafeRegex\Exception\SuspectedReturnSafeRegexException;
use TRegx\SafeRegex\ExceptionFactory;

class ExceptionFactoryTest extends TestCase
{
    protected function setUp()
    {
        (new ErrorsCleaner())->clear();
    }

    /**
     * @test
     * @dataProvider \Test\DataProviders::invalidPregPatterns()
     * @param string $invalidPattern
     */
    public function testCompileErrors(string $invalidPattern)
    {
        // given
        @preg_match($invalidPattern, '');

        // when
        $exception = (new ExceptionFactory())->retrieveGlobals('preg_match', false);

        // then
        $this->assertInstanceOf(CompileSafeRegexException::class, $exception);
    }

    /**
     * @test
     * @dataProvider \Test\DataProviders::invalidUtf8Sequences()
     * @param $description
     * @param $utf8
     */
    public function testRuntimeErrors(string $description, string $utf8)
    {
        // given
        @preg_match("/pattern/u", $utf8);

        // when
        $exception = (new ExceptionFactory())->retrieveGlobals('preg_match', false);

        // then
        $this->assertInstanceOf(RuntimeSafeRegexException::class, $exception);
    }

    /**
     * @test
     */
    public function testUnexpectedReturnError()
    {
        // given
        $result = false;

        // when
        $exception = (new ExceptionFactory())->retrieveGlobals('preg_match', $result);

        // then
        $this->assertInstanceOf(SuspectedReturnSafeRegexException::class, $exception);
        $this->assertEquals("Invoking preg_match() resulted in 'false'.", $exception->getMessage());
    }
}
