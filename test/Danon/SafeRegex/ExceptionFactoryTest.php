<?php
namespace Danon\SafeRegex;

use Danon\SafeRegex\Exception\PhpErrorSafeRegexException;
use Danon\SafeRegex\Exception\PregErrorSafeRegexException;
use PHPUnit\Framework\TestCase;

class ExceptionFactoryTest extends TestCase
{
    public function invalidPatterns()
    {
        return [
            ['/{2,1}/'],
            ['/)/'],
            ['/+/'],
            [' /\/'],
            ['/\/'],
            ['/\\/'],
            ['/(/'],
            ['/{1}/'],
        ];
    }

    /**
     * @dataProvider invalidPatterns
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

    public function invalidUtf8Sequences()
    {
        return [
            ['Invalid 2 Octet Sequence', "\xc3\x28"],
            ['Invalid Sequence Identifier', "\xa0\xa1"],
            ['Invalid 3 Octet Sequence (in 2nd Octet)', "\xe2\x28\xa1"],
            ['Invalid 3 Octet Sequence (in 3rd Octet)', "\xe2\x82\x28"],
            ['Invalid 4 Octet Sequence (in 2nd Octet)', "\xf0\x28\x8c\xbc"],
            ['Invalid 4 Octet Sequence (in 3rd Octet)', "\xf0\x90\x28\xbc"],
            ['Invalid 4 Octet Sequence (in 4th Octet)', "\xf0\x28\x8c\x28"],
        ];
    }

    /**
     * @dataProvider invalidUtf8Sequences
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
