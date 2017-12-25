<?php
namespace Test\SafeRegex;

use SafeRegex\Exception\PhpErrorSafeRegexException;
use SafeRegex\Exception\PregErrorSafeRegexException;
use SafeRegex\ExceptionFactory;
use PHPUnit\Framework\TestCase;

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

        // then
        $this->expectException(PhpErrorSafeRegexException::class);

        // when
        (new ExceptionFactory())->retrieveGlobalsAndThrow('preg_match', $result);
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

        // then
        $this->expectException(PregErrorSafeRegexException::class);

        // when
        (new ExceptionFactory())->retrieveGlobalsAndThrow('preg_match', $result);
    }
}
