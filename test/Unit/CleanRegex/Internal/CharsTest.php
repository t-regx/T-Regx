<?php
namespace Test\Unit\TRegx\CleanRegex\Internal;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Chars;

/**
 * @covers \TRegx\CleanRegex\Internal\Chars
 */
class CharsTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCastToString()
    {
        // given
        $foo = new Chars('Foo');

        // when
        $string = "$foo";

        // then
        $this->assertSame('Foo', $string);
    }

    /**
     * @test
     */
    public function testIdentity()
    {
        // given
        $car = new Chars('Car');

        // when + then
        $this->assertTrue($car->endsWith('Car'));
        $this->assertTrue($car->startsWith('Car'));
        $this->assertTrue($car->contains('Car'));
    }

    /**
     * @test
     */
    public function testEmpty()
    {
        // given
        $car = new Chars('Car');

        // when + then
        $this->assertTrue($car->endsWith(''));
        $this->assertTrue($car->startsWith(''));
        $this->assertTrue($car->contains(''));
    }

    /**
     * @test
     */
    public function shouldContain0BecausePhpSucks()
    {
        // given
        $empty = new Chars('');

        // when + then
        $this->assertFalse($empty->endsWith('0'));
        $this->assertFalse($empty->startsWith('0'));
        $this->assertFalse($empty->contains('0'));
    }

    /**
     * @test
     */
    public function testEmptyOnEmpty()
    {
        // given
        $empty = new Chars('');

        // when + then
        $this->assertTrue($empty->endsWith(''));
        $this->assertTrue($empty->startsWith(''));
        $this->assertTrue($empty->contains(''));
    }

    /**
     * @test
     */
    public function testEmptyForInfix()
    {
        // given
        $empty = new Chars('');

        // when + then
        $this->assertFalse($empty->endsWith('Car'));
        $this->assertFalse($empty->startsWith('Car'));
        $this->assertFalse($empty->contains('Car'));
    }

    /**
     * @test
     */
    public function shouldContain()
    {
        // given
        $foo = new Chars('Foo');

        // when + then
        $this->assertTrue($foo->contains('o'));
    }

    /**
     * @test
     */
    public function shouldNotContain()
    {
        // given
        $foo = new Chars('Foo');

        // when + then
        $this->assertFalse($foo->contains('O'));
    }

    /**
     * @test
     */
    public function shouldContainAtTheStart()
    {
        // given
        $foo = new Chars('Foo');

        // when + then
        $this->assertTrue($foo->contains('F'));
    }

    /**
     * @test
     */
    public function shouldInfixNotContainSubject()
    {
        // given
        $text = new Chars('F');

        // when + then
        $this->assertFalse($text->contains('Foo'));
    }

    /**
     * @test
     */
    public function shouldStartWith()
    {
        // given
        $text = new Chars('Foo');

        // when + then
        $this->assertTrue($text->startsWith('Fo'));
    }

    /**
     * @test
     */
    public function shouldNotStartWith()
    {
        // given
        $text = new Chars('Foo');

        // when + then
        $this->assertFalse($text->startsWith('Fr'));
    }

    /**
     * @test
     */
    public function shouldNotStartWithInfix()
    {
        // given
        $text = new Chars('Foo');

        // when + then
        $this->assertFalse($text->startsWith('oo'));
    }

    /**
     * @test
     */
    public function shouldEndWith()
    {
        // given
        $text = new Chars('Car door');

        // when + then
        $this->assertTrue($text->endsWith('oor'));
    }

    /**
     * @test
     */
    public function shouldEndWithMultipleOccurrences()
    {
        // given
        $text = new Chars('Car Car');

        // when + then
        $this->assertTrue($text->endsWith('Car'));
    }

    /**
     * @test
     */
    public function shouldNotEndWith()
    {
        // given
        $car = new Chars('Car');

        // when + then
        $this->assertFalse($car->endsWith('aR'));
    }

    /**
     * @test
     */
    public function shouldNotEndWithInfix()
    {
        // given
        $car = new Chars('Car');

        // when + then
        $this->assertFalse($car->endsWith('Ca'));
    }

    /**
     * @test
     */
    public function shouldNotContainMalformedUnicode()
    {
        // given
        $unicode = new Chars('łódź € łódź');

        // when + then
        $this->assertFalse($unicode->contains(\chr(226)));
        $this->assertFalse($unicode->contains(\chr(226) . \chr(130)));
        $this->assertTrue($unicode->contains('ź € ł'));
    }

    /**
     * @test
     */
    public function shouldNotContainMalformedUnicodeSecond()
    {
        // given
        $unicode = new Chars('łódź € łódź');

        // when + then
        $this->assertFalse($unicode->contains(\chr(226)));
        $this->assertFalse($unicode->contains(\chr(226) . \chr(130)));
        $this->assertTrue($unicode->contains('ź € ł'));
    }

    /**
     * @test
     */
    public function shouldNotEndWithMalformedUnicode()
    {
        // given
        $unicode = new Chars('łódź €');

        // when + then
        $this->assertFalse($unicode->endsWith(\chr(172)));
        $this->assertFalse($unicode->endsWith(\chr(130) . \chr(172)));
        $this->assertTrue($unicode->endsWith('ź €'));
    }

    /**
     * @test
     */
    public function shouldNotStartWithMalformedUnicode()
    {
        // given
        $unicode = new Chars('€ łódź');

        // when + then
        $this->assertFalse($unicode->startsWith(\chr(226)));
        $this->assertFalse($unicode->startsWith(\chr(226) . \chr(130)));
        $this->assertTrue($unicode->startsWith('€ ł'));
    }
}
