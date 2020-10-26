<?php
namespace Test\Feature\TRegx\CleanRegex;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;
use TRegx\SafeRegex\Exception\MalformedPatternException;

class PatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldTest_beFalse_forNotMatching()
    {
        // when
        $test = pattern('\d')->test('abc');

        // then
        $this->assertFalse($test);
    }

    /**
     * @test
     */
    public function shouldFails_beTrue_forNotMatched()
    {
        // when
        $fails = pattern('\d')->fails('abc');

        // then
        $this->assertTrue($fails);
    }

    /**
     * @test
     */
    public function shouldCount()
    {
        // when
        $count = pattern('\d+')->count('111-222-333');

        // then
        $this->assertEquals(3, $count);
    }

    /**
     * @test
     */
    public function shouldCount_0_notMatched()
    {
        // when
        $count = pattern('[a-z]+')->count('111-222-333');

        // then
        $this->assertEquals(0, $count);
    }

    /**
     * @test
     */
    public function shouldQuote()
    {
        // when
        $quoted = Pattern::quote('[a-z]+');

        // then
        $this->assertEquals('\[a\-z\]\+', $quoted);
    }

    /**
     * @test
     */
    public function should_unquote()
    {
        // when
        $unquoted = Pattern::unquote('\[a\-z\]\+');

        // then
        $this->assertEquals('[a-z]+', $unquoted);
    }

    /**
     * @test
     */
    public function shouldFilterArray()
    {
        // given
        $array = [
            'Uppercase',
            'lowercase',
            'Uppercase again',
            'lowercase again',
        ];

        // when
        $result = pattern('[A-Z][a-z]+')->forArray($array)->filter();

        // then
        $this->assertEquals(['Uppercase', 'Uppercase again'], $result);
    }

    /**
     * @test
     */
    public function shouldFilterArray_assoc()
    {
        // given
        $array = [
            'a' => 'Uppercase',
            'b' => 'lowercase',
            'c' => 'Uppercase again',
            'd' => 'lowercase again',
        ];

        // when
        $result = pattern('[A-Z][a-z]+')->forArray($array)->filterAssoc();

        // then
        $expected = ['a' => 'Uppercase', 'c' => 'Uppercase again'];
        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function shouldFilterArray_byKeys()
    {
        // given
        $array = [
            'Uppercase'       => 0,
            'lowercase'       => 1,
            'Uppercase again' => 2,
            'lowercase again' => 3,
        ];

        // when
        $result = pattern('[A-Z][a-z]+')->forArray($array)->filterByKeys();

        // then
        $expected = ['Uppercase' => 0, 'Uppercase again' => 2];
        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function shouldThrowMalformedPatternException_forUndelimitedPcrePattern()
    {
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Delimiter must not be alphanumeric or backslash');

        // when
        Pattern::pcre("foo")->test('bar');
    }

    /**
     * @test
     */
    public function shouldThrowPrettyErrorMessage(): void
    {
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage("Two named subpatterns have the same name at offset 21");

        // when
        pattern('First(?<one>)?(?<one>)?')->test('Test');
    }
}
