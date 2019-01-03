<?php
namespace Test\Feature\TRegx\CleanRegex;

use PHPUnit\Framework\TestCase;

class PatternTest extends TestCase
{
    /**
     * @test
     */
    public function should_matches()
    {
        // when
        $matches = pattern('\d')->matches('abc');

        // then
        $this->assertFalse($matches);
    }

    /**
     * @test
     */
    public function should_fails()
    {
        // when
        $matches = pattern('\d')->fails('abc');

        // then
        $this->assertTrue($matches);
    }

    /**
     * @test
     */
    public function should_count()
    {
        // when
        $count = pattern('\d+')->count('111-222-333');

        // then
        $this->assertEquals(3, $count);
    }

    /**
     * @test
     */
    public function should_count_unmatched()
    {
        // when
        $count = pattern('[a-z]+')->count('111-222-333');

        // then
        $this->assertEquals(0, $count);
    }

    /**
     * @test
     */
    public function should_quote()
    {
        // when
        $quoted = pattern('[a-z]+')->quote();

        // then
        $this->assertEquals('\[a\-z\]\+', $quoted);
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
        $result = pattern('[A-Z][a-z]+')->filter($array);

        // then
        $expected = ['Uppercase', 'Uppercase again'];
        $this->assertEquals($expected, $result);
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
        $result = pattern('[A-Z][a-z]+')->filterAssoc($array);

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
        $result = pattern('[A-Z][a-z]+')->filterByKeys($array);

        // then
        $expected = ['Uppercase' => 0, 'Uppercase again' => 2];
        $this->assertEquals($expected, $result);
    }
}
