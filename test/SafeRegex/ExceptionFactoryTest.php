<?php
namespace Test\SafeRegex;

use PHPUnit\Framework\TestCase;
use SafeRegex\Exception\CompileSafeRegexException;
use SafeRegex\Exception\RuntimeSafeRegexException;
use SafeRegex\ExceptionFactory;

class ExceptionFactoryTest extends TestCase
{
    /**
     * @dataProvider \Test\DataProviders::invalidPregPatterns()
     * @param string $invalidPattern
     */
    public function testPregErrors(string $invalidPattern)
    {
        // given
        $result = @preg_match($invalidPattern, '');

        // when
        $exception = (new ExceptionFactory())->retrieveGlobals('preg_match', $result);

        // then
        $this->assertInstanceOf(CompileSafeRegexException::class, $exception);
    }

    /**
     * @dataProvider \Test\DataProviders::invalidUtf8Sequences()
     * @param $description
     * @param $utf8
     */
    public function test(string $description, string $utf8)
    {
        // given
        $result = @preg_match("/pattern/u", $utf8);

        // when
        $exception = (new ExceptionFactory())->retrieveGlobals('preg_match', $result);

        // then
        $this->assertInstanceOf(RuntimeSafeRegexException::class, $exception);
    }
}
